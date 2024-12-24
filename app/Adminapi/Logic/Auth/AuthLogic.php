<?php

namespace App\Adminapi\Logic\Auth;

use App\Common\Model\Auth\AdminRole;
use App\Common\Model\Auth\SystemMenu;
use App\Common\Model\Auth\SystemRoleMenu;


/**
 * 权限功能类
 */
class AuthLogic
{
    /**
     * 获取全部权限
     *
     * @return array
     */
    public static function getAllAuth(): array
    {
        return SystemMenu::distinct()
            ->where([
                ['is_disable', '=', 0],
                ['perms', '<>', '']
            ])
            ->pluck('perms')
            ->toArray();
    }

    /**
     * 获取当前管理员角色按钮权限
     *
     * @param array $admin
     * @return array
     */
    public static function getBtnAuthByRoleId(array $admin): array
    {
        if ($admin['root']) {
            return ['*'];
        }

        $menuId = SystemRoleMenu::whereIn('role_id', $admin['role_id'])
            ->pluck('menu_id');

        $roleAuth = SystemMenu::query()
            ->whereIn('id', $menuId)
            ->where('is_disable', 0)
            ->where('perms', '<>', '')
            ->distinct()
            ->pluck('perms')
            ->toArray();

        $allAuth = SystemMenu::query()
            ->where('is_disable', 0)
            ->where('perms', '<>', '')
            ->distinct()
            ->pluck('perms')
            ->toArray();

        $hasAllAuth = array_diff($allAuth, $roleAuth);
        if (empty($hasAllAuth)) {
            return ['*'];
        }

        return $roleAuth;
    }

    /**
     * 获取管理员角色关联的菜单id(菜单，权限)
     *
     * @param int $adminId
     * @return array
     */
    public static function getAuthByAdminId(int $adminId): array
    {
        $roleIds = AdminRole::where('admin_id', $adminId)->pluck('role_id')->toArray();
        $menuId = SystemRoleMenu::whereIn('role_id', $roleIds)->pluck('menu_id')->toArray();

        return SystemMenu::distinct()
            ->applySearchWhere([
                ['is_disable', '=', 0],
                ['perms', '<>', ''],
                ['id', 'in', array_unique($menuId)],
            ])
            ->pluck('perms')
            ->toArray();
    }
}
