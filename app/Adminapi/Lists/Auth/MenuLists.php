<?php

namespace App\Adminapi\Lists\Auth;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Model\Auth\SystemMenu;

/**
 *  菜单列表
 */
class MenuLists extends BaseAdminDataLists
{

    /**
     * @notes 获取菜单列表
     * @return array
     */
    public function lists(): array
    {
        $lists = SystemMenu::query()
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
        return linear_to_tree($lists, 'children');
    }


    /**
     * @notes 获取菜单数量
     */
    public function count(): int
    {
        return SystemMenu::count();
    }

}
