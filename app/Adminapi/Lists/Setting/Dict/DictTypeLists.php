<?php

namespace App\Adminapi\Lists\Setting\Dict;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Lists\ListsSearchInterface;
use App\Common\Model\Dict\DictType;

/**
 * 字典类型列表
 */
class DictTypeLists extends BaseAdminDataLists implements ListsSearchInterface
{
    /**
     * @notes 设置搜索条件
     */
    public function setSearch(): array
    {
        return [
            '%like%' => ['name', 'type'],
            '=' => ['status']
        ];
    }


    /**
     * @notes 获取列表
     */
    public function lists(): array
    {
        return DictType::query()->applySearchWhere($this->searchWhere)
            ->limit($this->limitLength)
            ->offset($this->limitOffset)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }


    /**
     * @notes 获取数量
     */
    public function count(): int
    {
        return DictType::applySearchWhere($this->searchWhere)->count();
    }

}
