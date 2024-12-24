<?php

namespace App\Adminapi\Lists\Tools;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Lists\ListsSearchInterface;
use App\Common\Model\Tools\GenerateTable;

/**
 * 代码生成所选数据表列表
 */
class GenerateTableLists extends BaseAdminDataLists implements ListsSearchInterface
{

    /**
     * @notes 设置搜索条件
     */
    public function setSearch(): array
    {
        return [
            '%like%' => ['table_name', 'table_comment']
        ];
    }


    /**
     * @notes 查询列表
     */
    public function lists(): array
    {
        return GenerateTable::applySearchWhere($this->searchWhere)
            ->orderBy('id', 'desc')
            ->limit($this->limitLength)
            ->offset($this->limitOffset)
            ->get()
            ->toArray();
    }


    /**
     * @notes 获取数量
     * @return int
     * @author 段誉
     * @date 2022/6/14 10:55
     */
    public function count(): int
    {
        return GenerateTable::count();
    }

}
