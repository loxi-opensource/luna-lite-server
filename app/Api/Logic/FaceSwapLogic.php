<?php

namespace App\Api\Logic;

use App\Common\Enum\FileEnum;
use App\Common\Enum\User\AccountLogEnum;
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
use Illuminate\Support\Facades\DB;

class FaceSwapLogic extends DrawLogic
{
    static function createSwap(User $user, GenerateParams $params)
    {
        // 作图总数
        $drawNumber = 1;

        // 校验余额
        $checkResult = self::checkAbleDraw($user, $drawNumber);
        if ($checkResult !== true) {
            self::setError($checkResult);
            return false;
        }

        // 创建作图任务
        try {
            $resUrl = (new FaceSwapService())->swapFaces($params);
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }

        // 保存图片到OSS
        $resultImage = self::downloadImage($resUrl);
        if (empty($resultImage)) {
            self::setError('下载远程图片失败');
            return false;
        }

        DB::beginTransaction();
        try {
            // 保存到数据库
            $newRecord = new SwapRecord();
            $newRecord->user_id = $user->id;
            $newRecord->target_image = $params->getTargetImage();
            $newRecord->user_image = $params->getUserImage();
            $newRecord->result_image = $resultImage;
            $newRecord->save();

            // 扣除余额
            self::drawBalanceHandle($user, $drawNumber, AccountLogEnum::DRAW_DEC_IMAGE);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            self::setError($e->getMessage());
            return false;
        }

        return $newRecord;
    }

    static function createAvatar($userId)
    {
        try {
            $storageType = Cache::get('STORAGE_DEFAULT', 'local');
            if ($storageType != 'aliyun') {
                throw new \Exception('请先配置阿里云OSS存储');
            }

            // 上传阿里云
            $ossResult = UploadService::image(0, $userId, FileEnum::SOURCE_USER);
            $imageUrl = Arr::get($ossResult, 'uri');

            // 检测人脸
            $faceSwapService = new FaceSwapService();
            $faceList = $faceSwapService->detectFace($imageUrl);

            // 创建数字分身
            $cropService = new ImageCropService($imageUrl);

            DB::beginTransaction();
            foreach ($faceList as $face) {
                // 用户图有多少张人脸就创建多少个分身
                $newRow = [
                    'user_id' => $userId,
                    'user_image' => $imageUrl,
                    'face_image' => $cropService->cropByRatio(
                        $face['boundingBoxLeft'], $face['boundingBoxTop'],
                        $face['boundingBoxWidth'], $face['boundingBoxHeight']),
                    'face_id' => $face['id'],
                ];
                DigitalAvatar::create($newRow);
            }
            DB::commit();

            return ['user_image' => $imageUrl];
        } catch (\Exception $e) {
            DB::rollBack();
            self::setError($e->getMessage());
            return false;
        }
    }

    //    public function userRecords(Request $request)
    //    {
    //        $data = SwapRecord::query()
    //            ->where('user_id', $this->getUserId())
    //            ->orderBy('id', 'desc')
    //            ->simplePaginate($request->get('per_page', 10));
    //        $lists = Arr::get($data->toArray(), 'data', []);
    //        return $this->success("success", compact('lists'));
    //    }
    static function userRecords($userId, $perPage = 10)
    {
        $data = SwapRecord::query()
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->simplePaginate($perPage);
        $lists = Arr::get($data->toArray(), 'data', []);
        return $lists;
    }
}
