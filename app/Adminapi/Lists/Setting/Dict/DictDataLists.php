<?php

namespace App\Adminapi\Lists\Setting\Dict;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Lists\ListsSearchInterface;
use App\Common\Model\Dict\DictData;


/**
 * 字典数据列表
 */
class DictDataLists extends BaseAdminDataLists implements ListsSearchInterface
{

    /**
     * @notes 设置搜索条件
     */
    public function setSearch(): array
    {
        return [
            '%like%' => ['name', 'type_value'],
            '=' => ['status', 'type_id']
        ];
    }


    /**
     * @notes 获取列表
     */
    public function lists(): array
    {
        return DictData::query()->applySearchWhere($this->searchWhere)
            ->limit($this->limitLength)
            ->offset($this->limitOffset)
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }


    /**
     * @notes 获取数量
     */
    public function count(): int
    {
        return DictData::applySearchWhere($this->searchWhere)->count();
    }

}
