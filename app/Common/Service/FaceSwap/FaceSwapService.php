<?php

namespace App\Common\Service\FaceSwap;

use App\Common\Service\Aliyun\ImageCropService;
use App\Common\Service\ConfigService;
use App\Common\Types\Swap\GenerateParams;
use Firebase\JWT\JWT;
use Illuminate\Support\Arr;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FaceSwapService
{
    private string $baseUrl;
    private string $provider = 'ST'; // 商汤科技
    private string $ak;
    private string $sk;

    public function __construct()
    {
        $this->ak = ConfigService::get('faceSwapKey', 'ak', '');
        $this->sk = ConfigService::get('faceSwapKey', 'sk', '');

        if (empty($this->ak) || empty($this->sk)) {
            throw new \InvalidArgumentException('请先配置算法服务的密钥');
        }

        $this->baseUrl = 'https://mhapi.sensetime.com/v1/face';
    }

    // 人脸检测
    public function detectFace($imageUrl)
    {
        $detectResult = $this->detect($imageUrl);

        if (!isset($detectResult['task_id'])) {
            throw new \Exception("Target image detection failed: " . json_encode($detectResult));
        }

        $taskId = $detectResult['task_id'];
        $detectData = $this->pollForDetectResult($taskId);

        if (empty($detectData) or !isset($detectData['faces'])) {
            throw new \Exception("Failed to retrieve detection results for target image.");
        }

        // 获取原图尺寸
        $s = new ImageCropService($imageUrl);
        $originHeight = $s->getImageHeight();
        $originWidth = $s->getImageWidth();
        $faceList = [];
        foreach ($detectData['faces'] as $face) {
            // 统一数据结构
            $faceList[] = [
                // 人脸坐标 / 原图尺寸的比例
                'boundingBoxLeft' => $face['coords']['start_x'] / $originWidth,
                'boundingBoxTop' => $face['coords']['start_y'] / $originHeight,
                'boundingBoxWidth' => ($face['coords']['end_x'] - $face['coords']['start_x']) / $originWidth,
                'boundingBoxHeight' => ($face['coords']['end_y'] - $face['coords']['start_y']) / $originHeight,
                // 人脸ID
                'id' => strval($face['index']),
            ];
        }
        return $faceList;
    }

    public function swapFaces(GenerateParams $generateParams)
    {
        $params = $generateParams->toArray();
        $targetImageUrl = $params['target_image'];
        $userImageUrl = $params['user_image'];
        $faceMapping = $params['face_mapping'];

        $faces = [];
        foreach ($faceMapping as $targetIndex => $userIndex) {
            $targetIndex = intval(Str::replaceFirst('T#', '', $targetIndex));
            $userIndex = intval(Str::replaceFirst('U#', '', $userIndex));
            $faces[] = [
                'url' => $userImageUrl, // 用户图片URL
                'index' => $userIndex,  // 用户图片的脸索引
                'base_index' => $targetIndex, // 目标图片的脸索引
            ];
        }

        $generateResult = $this->generateImage($targetImageUrl, $faces);
        if (!isset($generateResult['task_id'])) {
            throw new \Exception("Image generation failed: " . json_encode($generateResult));
        }

        $generateTaskId = $generateResult['task_id'];

        // 统一数据结构
        $res = $this->pollForGenerateResult($generateTaskId);
        $imageUrl = Arr::get($res, 'img_raw_url');
        if (empty($imageUrl)) {
            throw new \Exception("Failed to retrieve generated image URL.");
        }
        return $imageUrl;
    }

    /**
     * 简单换脸。只需提供图片URL
     *
     * @param string $targetImageUrl 目标图片URL
     * @param string $userImageUrl 用户图片URL
     * @return array|null  返回生成的图片数据或 null 如果出现错误
     * @throws \Exception 如果出现错误
     */
    private function swapSimple(string $targetImageUrl, string $userImageUrl): ?array
    {
        $detectResult = $this->detect($targetImageUrl);

        if (!isset($detectResult['task_id'])) {
            throw new \Exception("Target image detection failed: " . json_encode($detectResult));
        }

        $targetTaskId = $detectResult['task_id'];
        $targetDetectData = $this->pollForDetectResult($targetTaskId);

        if (empty($targetDetectData) or !isset($targetDetectData['faces'])) {
            throw new \Exception("Failed to retrieve detection results for target image.");
        }

        $faces = [];
        foreach ($targetDetectData['faces'] as $index => $face) {
            $faces[] = [
                'url' => $userImageUrl, // 用户图片URL。不需要提前检测人脸
                'index' => 0, //  用户图片始终使用索引0，因为我们假设只有一张脸
                'base_index' => $index, // 目标图片的脸索引
            ];
        }

        $generateResult = $this->generateImage($targetImageUrl, $faces);

        if (!isset($generateResult['task_id'])) {
            throw new \Exception("Image generation failed: " . json_encode($generateResult));
        }

        $generateTaskId = $generateResult['task_id'];
        return $this->pollForGenerateResult($generateTaskId);
    }

    /**
     * 作图接口
     *
     * @param string $targetImageUrl 目标图片URL
     * @param array $faces 人脸列表
     * @return array|null
     */
    private function generateImage(string $targetImageUrl, array $faces): ?array
    {
        $data = [
            'model_id' => 'faceswap_v1',
            'base_img' => ['url' => $targetImageUrl],
            'faces' => $faces,
        ];

//        return $this->makeRequest('POST', '/imggen_sync', $data);
        return $this->makeRequest('POST', '/imggen', $data);
    }

    /**
     * 获取检测结果 (带轮询)
     * @param string $taskId
     * @return array|null
     */
    private function pollForDetectResult(string $taskId): ?array
    {
        return $this->pollForResult($taskId, '/detect/');
    }


    /**
     * 获取生成结果 (带轮询)
     * @param string $taskId
     * @return array|null
     */
    private function pollForGenerateResult(string $taskId): ?array
    {
        return $this->pollForResult($taskId, '/imggen/');
    }


    /**
     *  轮询结果的通用方法
     * @param string $taskId
     * @param string $endpoint 例如 '/detect/' 或 '/imggen_async/'
     * @return array|null
     */
    private function pollForResult(string $taskId, string $endpoint): ?array
    {
        $maxAttempts = 10;
        $attempts = 0;

        while ($attempts < $maxAttempts) {
            $result = $this->makeRequest('GET', $endpoint . $taskId);

            if (Arr::get($result, 'error')) {
                throw new \Exception("换脸失败: " . json_encode(Arr::get($result, 'error')));
            }

            $task = Arr::get($result, 'task');
            if ($task && Arr::get($task, 'state') == 1) {
                return $task;
            }

            sleep(1);
            $attempts++;
        }

        throw new \Exception("轮询超时");
    }

    /**
     * Encode JWT token.
     *
     * @param string $ak Access Key
     * @param string $sk Secret Key
     * @return string
     */
    protected function generateJwtToken(): string
    {
        $headers = [
            'alg' => 'HS256',
            'typ' => 'JWT',
        ];

        $payload = [
            'iss' => $this->ak,
            'exp' => time() + 1800, // 当前时间 + 30分钟
            'nbf' => time() - 5,    // 当前时间 - 5秒
        ];

        return JWT::encode($payload, $this->sk, 'HS256', null, $headers);
    }

    /**
     * 人脸检测
     *
     * @param string $imageUrl 图片URL
     * @return array|null
     */
    private function detect(string $imageUrl): ?array
    {
        return $this->makeRequest('POST', '/detect', ['img_url' => $imageUrl]);
    }

    /**
     * 检测结果查询
     *
     * @param string $requestId 请求ID
     * @return array|null
     */
    private function getDetectResult(string $requestId): ?array
    {
        return $this->makeRequest('GET', "/detect/{$requestId}");
    }


    /**
     * 发送请求到SenseTime API
     *
     * @param string $method 请求方法 (GET, POST, etc.)
     * @param string $endpoint API端点
     * @param array|null $data 请求数据 (可选)
     * @return array|null  返回API响应数据或 null 如果出现错误
     */
    private function makeRequest(string $method, string $endpoint, ?array $data = null): ?array
    {
        Log::debug("商汤换脸发起请求: {$method} {$endpoint}", compact('data'));
        $token = $this->generateJwtToken();

        if (!$token) {
            return null; // Or throw an exception
        }

        $url = $this->baseUrl . $endpoint;
        $options = [
            'headers' => [
                'Authorization' => "Bearer {$token}",
                'Content-Type' => 'application/json',
            ],
        ];
        $method = strtolower($method);
        /**
         * @var \Illuminate\Http\Client\Response $response
         */
        $response = Http::withHeaders($options['headers'])->$method($url, $data);

        if ($response->successful()) {
            Log::debug("商汤换脸请求响应: {$method} {$endpoint}", [
                'request' => compact('data'),
                'response' => $response->json()
            ]);
            return $response->json();
        } else {
            Log::error("商汤换脸请求失败: {$method} {$endpoint}", [
                'request' => compact('data'),
                'response' => $response->json(),
                'status_code' => $response->status()
            ]);
            return null;
        }
    }
}
