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
    public static function add($userId, $changeType, $action, $changeAmount, string $sourceSn = '', string $remark = '', array $extra = [])
    {
        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        $changeObject = AccountLogEnum::getChangeObject($changeType);
        if (!$changeObject) {
            return false;
        }

        switch ($changeObject) {
            // 用户余额
            case AccountLogEnum::UM:
                $left_amount = $user->user_money;
                break;
            // 其他
        }

        $data = [
            'sn' => generate_sn(UserAccountLog::class, 'sn', 20),
            'user_id' => $userId,
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
