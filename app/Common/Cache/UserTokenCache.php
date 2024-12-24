<?php

namespace App\Common\Cache;

use App\Common\Model\User\User;
use App\Common\Model\User\UserSession;

class UserTokenCache extends BaseCache
{
    private $prefix = 'token_user_';

    /**
     * @notes 通过token获取缓存用户信息
     * @param $token
     * @return array|false|mixed
     */
    public function getUserInfo($token)
    {
        //直接从缓存获取
        $userInfo = $this->get($this->prefix . $token);
        if ($userInfo) {
            return $userInfo;
        }

        //从数据获取信息被设置缓存(可能后台清除缓存）
        $userInfo = $this->setUserInfo($token);
        if ($userInfo) {
            return $userInfo;
        }

        return false;
    }


    /**
     * @notes 通过有效token设置用户信息缓存
     * @param $token
     * @return array|false|mixed
     */
    public function setUserInfo($token)
    {
        $userSession = UserSession::query()
            ->where([['token', '=', $token], ['expire_time', '>', time()]])
            ->first();
        if (empty($userSession)) {
            return [];
        }

        $user = User::where('id', '=', $userSession->user_id)
            ->first();

        $userInfo = [
            'user_id' => $user->id,
            'nickname' => $user->nickname,
            'token' => $token,
            'sn' => $user->sn,
            'mobile' => $user->mobile,
            'avatar' => $user->avatar,
            'terminal' => $userSession->terminal,
            'expire_time' => $userSession->expire_time,
        ];

        $ttl = new \DateTime(Date('Y-m-d H:i:s', $userSession->expire_time));
        $this->set($this->prefix . $token, $userInfo, $ttl);
        return $this->getUserInfo($token);
    }


    /**
     * @notes 删除缓存
     * @param $token
     * @return bool
     */
    public function deleteUserInfo($token)
    {
        return $this->delete($this->prefix . $token);
    }


}
