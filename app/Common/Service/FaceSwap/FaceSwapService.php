<?php

namespace App\Common\Service\FaceSwap;

use Firebase\JWT\JWT;
use Illuminate\Support\Arr;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FaceSwapService
{
    private $baseUrl;
    private $provider;
    private $ak;
    private $sk;

    public function __construct()
    {
        $this->provider = Env::get('FACE_SWAP_PROVIDER');
        if ($this->provider !== 'ST') {
            throw new \InvalidArgumentException('Unsupported provider. Please check your configuration.');
        }

        $this->ak = Env::get('FACE_SWAP_AK');
        $this->sk = Env::get('FACE_SWAP_SK');

        if (empty($this->ak) || empty($this->sk)) {
            throw new \InvalidArgumentException('AK or SK is missing. Please check your configuration.');
        }

        $this->baseUrl = 'https://mhapi.sensetime.com/v1/face';
    }

    /**
     * 执行换脸操作
     *
     * @param string $targetImageUrl 目标图片URL
     * @param string $userImageUrl 用户图片URL
     * @param string $modelId 模型ID (默认为'faceswap_v1')
     * @return array|null  返回生成的图片数据或 null 如果出现错误
     * @throws \Exception 如果出现错误
     */
    public function swapFaces(string $targetImageUrl, string $userImageUrl, string $modelId = 'faceswap_v1'): ?array
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
                'url' => $userImageUrl, // todo 是否可以不用提前检测用户图
                'index' => 0, //  用户图片始终使用索引0，因为我们假设只有一张脸
                'base_index' => $index,
            ];
        }

        $generateResult = $this->generateImage($modelId, $targetImageUrl, $faces);

        if (!isset($generateResult['task_id'])) {
            throw new \Exception("Image generation failed: " . json_encode($generateResult));
        }

        $generateTaskId = $generateResult['task_id'];
        return $this->pollForGenerateResult($generateTaskId);
    }

    /**
     * 作图接口
     *
     * @param string $modelId 模型ID
     * @param string $baseImageUrl 底图URL
     * @param array $faces 人脸列表
     * @return array|null
     */
    public function generateImage(string $modelId, string $baseImageUrl, array $faces): ?array
    {
        $data = [
            'model_id' => $modelId,
            'base_img' => ['url' => $baseImageUrl],
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

            $task = Arr::get($result, 'task');
            if ($task && Arr::get($task, 'state') == 1) {
                return $task;
            }

            sleep(2);
            $attempts++;
        }

        return null; // 轮询超时
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
    public function detect(string $imageUrl): ?array
    {
        return $this->makeRequest('POST', '/detect', ['img_url' => $imageUrl]);
    }

    /**
     * 检测结果查询
     *
     * @param string $requestId 请求ID
     * @return array|null
     */
    public function getDetectResult(string $requestId): ?array
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
        Log::debug("Making request to {$endpoint}", ['method' => $method, 'data' => $data]);
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
            Log::debug("Request successful", ['response' => $response->json()]);
            return $response->json();
        } else {
            Log::error("Request failed", ['status_code' => $response->getStatusCode()]);
            // Handle errors, log, return null, or throw exception
            return null;
        }
    }
}
