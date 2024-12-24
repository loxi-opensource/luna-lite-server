<?php

namespace App\Adminapi\Lists\Auth;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Model\Auth\AdminRole;
use App\Common\Model\Auth\SystemRole;

/**
 * 角色列表
 */
class RoleLists extends BaseAdminDataLists
{
    /**
     * @notes 角色列表
     */
    public function lists(): array
    {
        $lists = SystemRole::with(['roleMenuIndex'])
            ->select('id', 'name', 'desc', 'sort', 'create_time')
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->limit($this->limitLength)
            ->offset($this->limitOffset)
            ->get()
            ->toArray();

        foreach ($lists as $key => $role) {
            // 使用角色的人数
            $lists[$key]['num'] = AdminRole::where('role_id', $role['id'])->count();
            $menuId = array_column($role['role_menu_index'], 'menu_id');
            $lists[$key]['menu_id'] = $menuId;
            unset($lists[$key]['role_menu_index']);
        }

        return $lists;
    }

    /**
     * @notes 总记录数
     */
    public function count(): int
    {
        return SystemRole::count();
    }
}
