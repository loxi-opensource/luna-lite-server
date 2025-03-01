<?php

namespace App\Common\Cache;

use App\Common\Model\Auth\Admin;
use App\Common\Model\Auth\AdminSession;
use App\Common\Model\Auth\SystemRole;

/**
 * 管理员token缓存
 */
class AdminTokenCache extends BaseCache
{

    private $prefix = 'token_admin_';

    /**
     * @notes 通过token获取缓存管理员信息
     */
    public function getAdminInfo($token)
    {
        //直接从缓存获取
        $adminInfo = $this->get($this->prefix . $token);
        if ($adminInfo) {
            return $adminInfo;
        }

        //从数据获取信息被设置缓存(可能后台清除缓存）
        $adminInfo = $this->setAdminInfo($token);
        if ($adminInfo) {
            return $adminInfo;
        }

        return false;
    }

    /**
     * @notes 通过有效token设置管理信息缓存
     */
    public function setAdminInfo($token)
    {
        $adminSession = AdminSession::query()
            ->where([['token', '=', $token], ['expire_time', '>', time()]])
            ->first();
        if (empty($adminSession)) {
            return [];
        }
        $admin = Admin::query()
            ->where('id', '=', $adminSession->admin_id)
            ->first();

        $roleName = '';
        $roleLists = SystemRole::query()->pluck('name', 'id');
        if ($admin['root'] == 1) {
            $roleName = '系统管理员';
        } else {
            foreach ($admin['role_id'] as $roleId) {
                $roleName .= $roleLists[$roleId] ?? '';
                $roleName .= '/';
            }
            $roleName = trim($roleName, '/');
        }

        $adminInfo = [
            'admin_id' => $admin->id,
            'root' => $admin->root,
            'name' => $admin->name,
            'account' => $admin->account,
            'role_name' => $roleName,
            'role_id' => $admin->role_id,
            'token' => $token,
            'terminal' => $adminSession->terminal,
            'expire_time' => $adminSession->expire_time,
            'login_ip' => request()->ip(),
        ];
        $this->set($this->prefix . $token, $adminInfo, new \DateTime(Date('Y-m-d H:i:s', $adminSession->expire_time)));
        return $this->getAdminInfo($token);
    }

    /**
     * @notes 删除缓存
     * @param $token
     * @return bool
     * @author 令狐冲
     * @date 2021/7/3 16:57
     */
    public function deleteAdminInfo($token)
    {
        return $this->delete($this->prefix . $token);
    }


}
