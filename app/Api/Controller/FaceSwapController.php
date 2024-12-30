<?php

namespace App\Api\Controller;

//use app\api\logic\LunaDrawLogic;
use App\Common\Enum\FileEnum;
use App\Common\Model\DigitalAvatar;
use App\Common\Model\User\User;
use App\Common\Service\Aliyun\ImageCropService;
use App\Common\Service\FaceSwap\FaceSwapService;
use App\Common\Service\UploadService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

//use app\common\model\luna\SwapTask;

//use app\common\service\luna\LunaDrawService;

//use app\common\types\user_draft\Draft;

class FaceSwapController extends BaseApiController
{
    // 发起AI换脸作图任务
    function submitDrawingV3()
    {
        // 校验通过，发起任务
        $result = LunaDrawLogic::submitTaskV3(
            User::find($this->userId),
            new Draft($this->request->post('draft')),
        );
        if (!$result) {
            $errMsg = LunaDrawLogic::getError();
            if (Str::contains($errMsg, '创建任务失败')) {
                return $this->fail('Fail to create a oreder, please contact customer service');
            }
            return $this->fail($errMsg);
        }

        return $this->success("success", $result);
    }


    // 上传用户图，制作数字分身（用户图有多少张人脸就创建多少个分身）
    public function uploadImage(FaceSwapService $faceSwapService)
    {
        try {
            $storageType = Cache::get('STORAGE_DEFAULT', 'local');
            if ($storageType != 'aliyun') {
                throw new \Exception('请先配置阿里云OSS存储');
            }

            // todo 图片安全检测

            $ossResult = UploadService::image(0, $this->getUserId(), FileEnum::SOURCE_USER);
            $imageUrl = $ossResult['uri'];
            $faceList = $faceSwapService->detectFace($imageUrl);

            // 创建数字分身
            $image = new ImageCropService($imageUrl);

            foreach ($faceList as $face) {
                $newRow = [
                    'user_id' => $this->getUserId(),
                    'user_image' => $imageUrl,
                    'face_image' => $image->cropByRatio($face['boundingBoxLeft'], $face['boundingBoxTop'], $face['boundingBoxWidth'], $face['boundingBoxHeight']),
                    'face_id' => $face['id'],
                ];
                DigitalAvatar::create($newRow);
            }

            return $this->success('Upload success');
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    // 用户作图记录
    function myGalleryListV3()
    {
        $data = (new SwapTask())
            ->where('user_id', $this->userId)
            ->order('id desc')
            ->paginate($this->request->get('per_page', 10));

        $data = $data->toArray();
        if (empty($data['data'])) {
            return $this->success("success", $data);
        }

        return $this->success("success", $data);
    }

}
