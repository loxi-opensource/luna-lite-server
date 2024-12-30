<?php

namespace App\Api\Logic;

use app\common\enum\QueueNameConst;
use App\Common\Enum\User\AccountLogEnum;
use app\common\model\luna\SwapTask;
use app\common\model\swap_template\SwapTemplate;
use app\common\model\swap_template\SwapTemplateCollectionRelation;
use app\common\model\user\User;
use App\Common\Service\FaceSwap\FaceSwapService;
use app\common\service\luna\LunaDrawService;
use App\Common\Types\Swap\FaceMapping;
use App\Common\Types\Swap\GenerateParams;
use app\common\types\user_draft\Draft;
use app\common\utils\LogUtils;
use think\facade\Db;
use think\facade\Log;

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
            $res = (new FaceSwapService())->swapFaces($params);
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }

        Db::startTrans();
        try {
            // 保存到数据库
            $newTask = new SwapTask();
            $newTask->user_id = $user->id;
            $newTask->up_task_id = $res['messageId'];
            $newTask->draw_number = $drawNumber;
            $newTask->face_mapping = $faceMapping->toArray();
            $newTask->user_draft = $draft->toArray();
            $newTask->strategy_id = $draft->strategy_id;
            $newTask->status = SwapTask::STATUS_PROCESSING;
            $newTask->save();

            // 扣除余额
            self::drawBalanceHandle($user, $drawNumber, AccountLogEnum::DRAW_DEC_IMAGE);

            //          // 生成失败, 返还用户余额
            //                self::drawBalanceHandle($userId, $taskExist->draw_number, AccountLogEnum::DRAW_INC_DRAW_FAIL);
            //
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }

        return $res;
    }
}
