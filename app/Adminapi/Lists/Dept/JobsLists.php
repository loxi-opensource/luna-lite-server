<?php

namespace App\Adminapi\Lists\Dept;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Lists\ListsExcelInterface;
use App\Common\Lists\ListsSearchInterface;
use App\Common\Model\Dept\Jobs;

/**
 * 岗位列表
 */
class JobsLists extends BaseAdminDataLists implements ListsSearchInterface, ListsExcelInterface
{

    /**
     * @notes 设置搜索条件
     */
    public function setSearch(): array
    {
        return [
            '%like%' => ['name'],
            '=' => ['code', 'status']
        ];
    }


    /**
     * @notes  获取管理列表
     */
    public function lists(): array
    {
        $lists = Jobs::applySearchWhere($this->searchWhere)
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->limit($this->limitLength)
            ->offset($this->limitOffset)
            ->get()
            ->toArray();

        return $lists;
    }


    /**
     * @notes 获取数量
     */
    public function count(): int
    {
        return Jobs::applySearchWhere($this->searchWhere)->count();
    }


    /**
     * @notes 导出文件名
     */
    public function setFileName(): string
    {
        return '岗位列表';
    }


    /**
     * @notes 导出字段
     */
    public function setExcelFields(): array
    {
        return [
            'code' => '岗位编码',
            'name' => '岗位名称',
            'remark' => '备注',
            'status_desc' => '状态',
            'create_time' => '添加时间',
        ];
    }

}
