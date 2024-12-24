<?php

namespace App\Adminapi\Lists\File;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Lists\ListsSearchInterface;
use App\Common\Model\File\FileCate;

/**
 * 文件分类列表
 */
class FileCateLists extends BaseAdminDataLists implements ListsSearchInterface
{
    /**
     * @notes 文件分类搜素条件
     */
    public function setSearch(): array
    {
        return [
            '=' => ['type']
        ];
    }


    /**
     * @notes 获取文件分类列表
     */
    public function lists(): array
    {
        $lists = FileCate::query()
            ->select(['id', 'pid', 'type', 'name'])
            ->where($this->searchWhere)
            ->orderBy('id', 'desc')->get()->toArray();

        return linear_to_tree($lists, 'children');
    }


    /**
     * @notes 获取文件分类数量
     */
    public function count(): int
    {
        return FileCate::query()->where($this->searchWhere)->count();
    }
}
