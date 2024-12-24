<?php

namespace App\Adminapi\Logic;

use App\Adminapi\Service\AdminTokenService;
use App\Common\Logic\BaseLogic;
use App\Common\Model\Auth\Admin;
use App\Common\Service\FileService;
use Illuminate\Support\Facades\Config;

/**
 * 登录逻辑
 */
class LoginLogic extends BaseLogic
{
    /**
     * @notes 管理员账号登录
     * @param $params
     */
    public function login($params)
    {
        $time = time();
        $admin = Admin::query()->where('account', '=', $params['account'])->first();

        //用户表登录信息更新
        $admin->login_time = $time;
        $admin->login_ip = request()->ip();
        $admin->save();

        //设置token
        $adminInfo = AdminTokenService::setToken($admin->id, $params['terminal'], $admin->multipoint_login);

        //返回登录信息
        $avatar = $admin->avatar ? $admin->avatar : Config::get('project.default_image.admin_avatar');
        $avatar = FileService::getFileUrl($avatar);
        return [
            'name' => $adminInfo['name'],
            'avatar' => $avatar,
            'role_name' => $adminInfo['role_name'],
            'token' => $adminInfo['token'],
        ];
    }


    /**
     * @notes 退出登录
     * @param $adminInfo
     */
    public function logout($adminInfo)
    {
        //token不存在，不注销
        if (!isset($adminInfo['token'])) {
            return false;
        }
        //设置token过期
        return AdminTokenService::expireToken($adminInfo['token']);
    }
}
