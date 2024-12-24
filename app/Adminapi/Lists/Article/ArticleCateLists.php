<?php

namespace App\Adminapi\Lists\Article;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Lists\ListsSearchInterface;
use App\Common\Lists\ListsSortInterface;
use App\Common\Model\Article\ArticleCate;

/**
 * 资讯分类列表
 */
class ArticleCateLists extends BaseAdminDataLists implements ListsSearchInterface, ListsSortInterface
{
    /**
     * @notes  设置搜索条件
     * @return array
     */
    public function setSearch(): array
    {
        return [];
    }

    /**
     * @notes  设置支持排序字段
     * @return array
     */
    public function setSortFields(): array
    {
        return ['create_time' => 'create_time', 'id' => 'id'];
    }

    /**
     * @notes  设置默认排序
     * @return array
     */
    public function setDefaultOrder(): array
    {
        return ['sort' => 'desc', 'id' => 'desc'];
    }

    /**
     * @notes  获取管理列表
     * @return array
     */
    public function lists(): array
    {
        return ArticleCate::query()
            ->applySearchWhere($this->searchWhere)
            ->applySortOrder($this->sortOrder)
            ->offset($this->limitOffset)
            ->limit($this->limitLength)
            ->get()
            ->toArray();
    }

    /**
     * @notes  获取数量
     * @return int
     */
    public function count(): int
    {
        return ArticleCate::query()
            ->applySearchWhere($this->searchWhere)
            ->count();
    }

    public function extend()
    {
        return [];
    }
}
