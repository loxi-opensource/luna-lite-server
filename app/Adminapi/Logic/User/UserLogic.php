<?php

namespace App\Adminapi\Logic\User;

use App\Common\Enum\User\AccountLogEnum;
use App\Common\Enum\User\UserTerminalEnum;
use App\Common\Logic\AccountLogLogic;
use App\Common\Logic\BaseLogic;
use App\Common\Model\User\User;
use Illuminate\Support\Facades\DB;

class UserLogic extends BaseLogic
{
    /**
     * @notes 用户详情
     */
    public static function detail(int $userId): array
    {
        $field = [
            'id', 'sn', 'account', 'nickname', 'avatar', 'real_name',
            'sex', 'mobile', 'create_time', 'login_time', 'channel',
            'user_money',
        ];

        $user = User::select($field)->find($userId);
        $user['channel'] = UserTerminalEnum::getTermInalDesc($user['channel']);
        return $user->toArray();
    }

    /**
     * @notes 更新用户信息
     */
    public static function setUserInfo(array $params)
    {
        return User::where('id', $params['id'])
            ->update([$params['field'] => $params['value']]);
    }

    /**
     * @notes 调整用户余额
     */
    public static function adjustUserMoney(array $params)
    {
        DB::beginTransaction();
        try {
            $user = User::find($params['user_id']);
            if (AccountLogEnum::INC == $params['action']) {
                $user->user_money += $params['num'];
                $user->save();
                AccountLogLogic::add(
                    $user->id,
                    AccountLogEnum::UM_INC_ADMIN,
                    AccountLogEnum::INC,
                    $params['num'],
                    '',
                    $params['remark'] ?? ''
                );
            } else {
                $user->user_money -= $params['num'];
                $user->save();
                AccountLogLogic::add(
                    $user->id,
                    AccountLogEnum::UM_DEC_ADMIN,
                    AccountLogEnum::DEC,
                    $params['num'],
                    '',
                    $params['remark'] ?? ''
                );
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
