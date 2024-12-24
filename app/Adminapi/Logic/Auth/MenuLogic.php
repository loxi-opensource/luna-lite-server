<?php

namespace App\Adminapi\Logic\Auth;

use App\Common\Enum\YesNoEnum;
use App\Common\Logic\BaseLogic;
use App\Common\Model\Auth\Admin;
use App\Common\Model\Auth\SystemMenu;
use App\Common\Model\Auth\SystemRoleMenu;

/**
 * 系统菜单
 * Class MenuLogic
 * @package App\Http\Logic\Auth
 */
class MenuLogic extends BaseLogic
{
    /**
     * @notes 获取管理员对应的角色菜单
     * @param int $adminId
     * @return array
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function getMenuByAdminId(int $adminId): array
    {
        $admin = Admin::findOrFail($adminId);
        $where = [
            ['is_disable', '=', 0],
        ];
        $baseQuery = SystemMenu::query()->where($where)->whereIn('type', ['M', 'C']);

        if ($admin->root != 1) {
            $roleMenu = SystemRoleMenu::query()->where('role_id', $admin->role_id)->pluck('menu_id');
            $baseQuery->whereIn('id', $roleMenu);
        }

        $menu = $baseQuery
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'asc')
            ->get();
        return linear_to_tree($menu, 'children');
    }

    /**
     * @notes 添加菜单
     * @param array $params
     * @return SystemMenu
     */
    public static function add(array $params): SystemMenu
    {
        return SystemMenu::create([
            'pid' => $params['pid'],
            'type' => $params['type'],
            'name' => $params['name'],
            'icon' => $params['icon'] ?? '',
            'sort' => $params['sort'],
            'perms' => $params['perms'] ?? '',
            'paths' => $params['paths'] ?? '',
            'component' => $params['component'] ?? '',
            'selected' => $params['selected'] ?? '',
            'params' => $params['params'] ?? '',
            'is_cache' => $params['is_cache'],
            'is_show' => $params['is_show'],
            'is_disable' => $params['is_disable'],
        ]);
    }

    /**
     * @notes 编辑菜单
     * @param array $params
     * @return bool
     */
    public static function edit(array $params): bool
    {
        return SystemMenu::where('id', $params['id'])->update([
            'pid' => $params['pid'],
            'type' => $params['type'],
            'name' => $params['name'],
            'icon' => $params['icon'] ?? '',
            'sort' => $params['sort'],
            'perms' => $params['perms'] ?? '',
            'paths' => $params['paths'] ?? '',
            'component' => $params['component'] ?? '',
            'selected' => $params['selected'] ?? '',
            'params' => $params['params'] ?? '',
            'is_cache' => $params['is_cache'],
            'is_show' => $params['is_show'],
            'is_disable' => $params['is_disable'],
        ]);
    }

    /**
     * @notes 详情
     * @param array $params
     * @return array
     */
    public static function detail(array $params): array
    {
        return SystemMenu::findOrFail($params['id'])->toArray();
    }

    /**
     * @notes 删除菜单
     * @param array $params
     */
    public static function delete(array $params): void
    {
        // 删除菜单
        SystemMenu::destroy($params['id']);
        // 删除角色-菜单表中与该菜单关联的记录
        SystemRoleMenu::where('menu_id', $params['id'])->delete();
    }

    /**
     * @notes 更新状态
     * @param array $params
     * @return bool
     */
    public static function updateStatus(array $params): bool
    {
        return SystemMenu::where('id', $params['id'])->update([
            'is_disable' => $params['is_disable'],
        ]);
    }

    /**
     * @notes 全部数据
     * @return array
     */
    public static function getAllData(): array
    {
        $data = SystemMenu::where('is_disable', YesNoEnum::NO)
            ->select('id', 'pid', 'name')
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        return linear_to_tree($data, 'children');
    }
}
