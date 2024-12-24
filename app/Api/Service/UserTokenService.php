<?php

namespace App\Api\Service;

use App\Common\Cache\UserTokenCache;
use App\Common\Model\User\UserSession;
use Illuminate\Support\Facades\Config;

class UserTokenService
{

    /**
     * @notes 设置或更新用户token
     * @param $userId
     * @param $terminal
     * @return array|false|mixed
     */
    public static function setToken($userId, $terminal)
    {
        $time = time();
        $userSession = UserSession::where([['user_id', '=', $userId], ['terminal', '=', $terminal]])->first();

        //获取token延长过期的时间
        $expireTime = $time + Config::get('project.user_token.expire_duration');
        $userTokenCache = new UserTokenCache();

        //token处理
        if ($userSession) {
            //清空缓存
            $userTokenCache->deleteUserInfo($userSession->token);
            //重新获取token
            $userSession->token = create_token($userId);
            $userSession->expire_time = $expireTime;
            $userSession->update_time = $time;
            $userSession->save();
        } else {
            //找不到在该终端的token记录，创建token记录
            $userSession = UserSession::create([
                'user_id' => $userId,
                'terminal' => $terminal,
                'token' => create_token($userId),
                'expire_time' => $expireTime
            ]);
        }

        return $userTokenCache->setUserInfo($userSession->token);
    }


    /**
     * @notes 延长token过期时间
     * @param $token
     * @return array|false|mixed
     */
    public static function overtimeToken($token)
    {
        $time = time();
        $userSession = UserSession::where('token', '=', $token)->first();
        if (!$userSession) {
            return false;
        }
        //延长token过期时间
        $userSession->expire_time = $time + Config::get('project.user_token.expire_duration');
        $userSession->update_time = $time;
        $userSession->save();

        return (new UserTokenCache())->setUserInfo($userSession->token);
    }


    /**
     * @notes 设置token为过期
     * @param $token
     * @return bool
     */
    public static function expireToken($token)
    {
        $userSession = UserSession::where('token', '=', $token)
            ->first();
        if (empty($userSession)) {
            return false;
        }

        $time = time();
        $userSession->expire_time = $time;
        $userSession->update_time = $time;
        $userSession->save();

        return (new  UserTokenCache())->deleteUserInfo($token);
    }

}
