<?php

namespace App\Adminapi\Service;

use App\Common\Cache\AdminTokenCache;
use App\Common\Model\Auth\AdminSession;
use Illuminate\Support\Facades\Config;

/**
 * 管理员token
 */
class AdminTokenService
{
    /**
     * @notes 设置或更新管理员token
     * @param $adminId //管理员id
     * @param $terminal //多终端名称
     * @param $multipointLogin //是否支持多处登录
     */
    public static function setToken($adminId, $terminal, $multipointLogin = 1)
    {
        $time = time();
        $adminSession = AdminSession::query()
            ->where([['admin_id', '=', $adminId], ['terminal', '=', $terminal]])->first();

        //获取token延长过期的时间
        $expireTime = $time + Config::get('project.admin_token.expire_duration');
        $adminTokenCache = new AdminTokenCache();

        //token处理
        if ($adminSession) {
            if ($adminSession->expire_time < $time || $multipointLogin === 0) {
                //清空缓存
                $adminTokenCache->deleteAdminInfo($adminSession->token);
                //如果token过期或账号设置不支持多处登录，更新token
                $adminSession->token = create_token($adminId);
            }
            $adminSession->expire_time = $expireTime;
            $adminSession->update_time = $time;

            $adminSession->save();
        } else {
            //找不到在该终端的token记录，创建token记录
            $adminSession = AdminSession::query()->create([
                'admin_id' => $adminId,
                'terminal' => $terminal,
                'token' => create_token($adminId),
                'expire_time' => $expireTime
            ]);
        }

        return $adminTokenCache->setAdminInfo($adminSession->token);
    }

    /**
     * @notes 延长token过期时间
     * @param $token
     */
    public static function overtimeToken($token)
    {
        $time = time();
        $adminSession = AdminSession::query()->where('token', '=', $token)->first();
        if (!$adminSession) {
            return false;
        }
        //延长token过期时间
        $adminSession->expire_time = $time + Config::get('project.admin_token.expire_duration');
        $adminSession->update_time = $time;
        $adminSession->save();
        return (new AdminTokenCache())->setAdminInfo($adminSession->token);
    }

    /**
     * @notes 设置token为过期
     * @param $token
     */
    public static function expireToken($token)
    {
        $adminSession = AdminSession::query()
            ->where('token', '=', $token)
            ->with('admin')
            ->first();
        if (!$adminSession) {
            return false;
        }

        //当支持多处登录的时候，服务端不注销
        if ($adminSession->admin->multipoint_login === 1) {
            return false;
        }

        $time = time();
        $adminSession->expire_time = $time;
        $adminSession->update_time = $time;
        $adminSession->save();

        return (new AdminTokenCache())->deleteAdminInfo($token);
    }

}
