<?php

namespace App\Adminapi\Logic\Auth;

use App\Common\Cache\AdminAuthCache;
use App\Common\Logic\BaseLogic;
use App\Common\Model\Auth\SystemRole;
use App\Common\Model\Auth\SystemRoleMenu;
use Illuminate\Support\Facades\DB;

/**
 * 角色逻辑层
 * Class RoleLogic
 * @package app\adminapi\logic\auth
 */
class RoleLogic extends BaseLogic
{
    /**
     * 添加角色
     * @param array $params
     * @return bool
     */
    public static function add(array $params): bool
    {
        DB::beginTransaction();
        try {
            $menuId = !empty($params['menu_id']) ? $params['menu_id'] : [];

            $role = SystemRole::query()->create([
                'name' => $params['name'],
                'desc' => $params['desc'] ?? '',
                'sort' => $params['sort'] ?? 0,
            ]);

            $data = [];
            foreach ($menuId as $item) {
                if (empty($item)) {
                    continue;
                }
                $data[] = [
                    'role_id' => $role->id,
                    'menu_id' => $item,
                ];
            }
            SystemRoleMenu::query()->insert($data);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * 编辑角色
     * @param array $params
     * @return bool
     */
    public static function edit(array $params): bool
    {
        DB::beginTransaction();
        try {
            $menuId = !empty($params['menu_id']) ? $params['menu_id'] : [];

            SystemRole::where('id', $params['id'])->update([
                'name' => $params['name'],
                'desc' => $params['desc'] ?? '',
                'sort' => $params['sort'] ?? 0,
            ]);

            if (!empty($menuId)) {
                // todo bug: 如果取消某个角色的全部权限，menu_id是空数组，不会走到删除逻辑。结果是一个权限也不会被删除
                SystemRoleMenu::where('role_id', $params['id'])->delete();
                $data = [];
                foreach ($menuId as $item) {
                    $data[] = [
                        'role_id' => $params['id'],
                        'menu_id' => $item,
                    ];
                }
                SystemRoleMenu::insert($data);
            }

            (new AdminAuthCache())->deleteTag();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * 删除角色
     * @param int $id
     * @return bool
     */
    public static function delete(int $id)
    {
        SystemRole::destroy($id);
        (new AdminAuthCache())->deleteTag();
        return true;
    }

    /**
     * 角色详情
     * @param int $id
     * @return array
     */
    public static function detail(int $id): array
    {
        $detail = SystemRole::select('id', 'name', 'desc', 'sort')->find($id);
        $authList = $detail->roleMenuIndex()->get()->toArray(); // todo
        $menuId = array_column($authList, 'menu_id');
        $detail->menu_id = $menuId;
        return $detail->toArray();
    }

    /**
     * 角色数据
     * @return array
     */
    public static function getAllData()
    {
        return SystemRole::orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }
}
