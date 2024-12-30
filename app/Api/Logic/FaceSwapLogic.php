<?php

namespace App\Api\Logic;

use App\Common\Enum\User\AccountLogEnum;
use App\Common\Model\SwapRecord;
use App\Common\Model\User\User;
use App\Common\Service\FaceSwap\FaceSwapService;
use App\Common\Types\Swap\GenerateParams;
use Illuminate\Support\Facades\DB;

class FaceSwapLogic extends DrawLogic
{
    static function generate(User $user, GenerateParams $params)
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
}
