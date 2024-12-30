<?php

namespace App\Api\Controller;

use App\Api\Logic\FaceSwapLogic;
use App\Common\Enum\FileEnum;
use App\Common\Model\DigitalAvatar;
use App\Common\Model\SwapRecord;
use App\Common\Model\User\User;
use App\Common\Service\Aliyun\ImageCropService;
use App\Common\Service\FaceSwap\FaceSwapService;
use App\Common\Service\UploadService;
use App\Common\Types\Swap\GenerateParams;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class FaceSwapController extends BaseApiController
{
    // 生成图片
    public function createSwap(Request $request)
    {
        try {
            $params = new GenerateParams(
                $request->post('target_image'),
                $request->post('user_image'),
                $request->post('face_mapping')
            );
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }

        $result = FaceSwapLogic::generate(User::query()->findOrFail($this->getUserId()), $params);
        if (!$result) {
            $errMsg = FaceSwapLogic::getError();
            return $this->fail($errMsg);
        }

        return $this->success("success", compact('result'));
    }

    // 上传用户图，制作数字分身。用户图有多少张人脸就创建多少个分身。
    public function createAvatar(FaceSwapService $faceSwapService)
    {
        try {
            $storageType = Cache::get('STORAGE_DEFAULT', 'local');
            if ($storageType != 'aliyun') {
                throw new \Exception('请先配置阿里云OSS存储');
            }

            // 上传阿里云
            $ossResult = UploadService::image(0, $this->getUserId(), FileEnum::SOURCE_USER);
            $imageUrl = Arr::get($ossResult, 'uri');

            // 检查图片是否合规
//            $isGreenImage = (new ImageGreenCheckService())->checkImage($imageUrl);
//            if (!$isGreenImage) {
//                return $this->fail('图片涉嫌违规，请重新上传');
//            }

            // 检测人脸
            $faceList = $faceSwapService->detectFace($imageUrl);

            // 创建数字分身
            $cropService = new ImageCropService($imageUrl);
            foreach ($faceList as $face) {
                $newRow = [
                    'user_id' => $this->getUserId(),
                    'user_image' => $imageUrl,
                    'face_image' => $cropService->cropByRatio(
                        $face['boundingBoxLeft'], $face['boundingBoxTop'],
                        $face['boundingBoxWidth'], $face['boundingBoxHeight']),
                    'face_id' => $face['id'],
                ];
                DigitalAvatar::create($newRow);
            }

            return $this->success('success', ['user_image' => $imageUrl]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    // 用户作图记录
    public function userRecords(Request $request)
    {
        $data = SwapRecord::query()
            ->where('user_id', $this->getUserId())
            ->orderBy('id', 'desc')
            ->simplePaginate($request->get('per_page', 10));
        $lists = Arr::get($data->toArray(), 'data', []);
        return $this->success("success", compact('lists'));
    }

}
