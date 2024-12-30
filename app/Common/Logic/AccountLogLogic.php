<?php

namespace App\Common\Logic;

use App\Common\Enum\User\AccountLogEnum;
use App\Common\Model\User\User;
use App\Common\Model\User\UserAccountLog;

class AccountLogLogic extends BaseLogic
{
    /**
     * @notes 账户流水记录
     */
    public static function add(User $user, $changeType, $action, $changeAmount, string $sourceSn = '', string $remark = '', array $extra = [])
    {
        $changeObject = AccountLogEnum::getChangeObject($changeType);
        if (!$changeObject) {
            return false;
        }

        switch ($changeObject) {
            // 对话余额
            case AccountLogEnum::UM:
                $left_amount = $user->balance;
                break;
            // 可提现佣金
            case AccountLogEnum::MONEY:
                $left_amount = $user->user_money;
                break;
            // 作图余额
            case AccountLogEnum::DRAW:
                $left_amount = $user->balance_draw;
                break;
        }

        $data = [
            'sn' => generate_sn(UserAccountLog::class, 'sn', 20),
            'user_id' => $user->id,
            'change_object' => $changeObject,
            'change_type' => $changeType,
            'action' => $action,
            'left_amount' => $left_amount,
            'change_amount' => $changeAmount,
            'source_sn' => $sourceSn,
            'remark' => $remark,
            'extra' => $extra ? json_encode($extra, JSON_UNESCAPED_UNICODE) : '',
        ];

        return UserAccountLog::create($data);
    }
}
