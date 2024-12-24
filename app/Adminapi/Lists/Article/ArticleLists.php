<?php

namespace App\Adminapi\Lists\Article;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Lists\ListsSearchInterface;
use App\Common\Lists\ListsSortInterface;
use App\Common\Model\Article\Article;

/**
 * 资讯列表
 */
class ArticleLists extends BaseAdminDataLists implements ListsSearchInterface, ListsSortInterface
{
    /**
     * @notes  设置搜索条件
     */
    public function setSearch(): array
    {
        return [
            '%like%' => ['title'],
            '=' => ['cid', 'is_show']
        ];
    }

    /**
     * @notes  设置支持排序字段
     */
    public function setSortFields(): array
    {
        return ['create_time' => 'create_time', 'id' => 'id'];
    }

    /**
     * @notes  设置默认排序
     */
    public function setDefaultOrder(): array
    {
        return ['sort' => 'desc', 'id' => 'desc'];
    }

    /**
     * @notes  获取管理列表
     */
    public function lists(): array
    {
        $articleLists = Article::query()
            ->applySearchWhere($this->searchWhere)
            ->applySortOrder($this->sortOrder)
            ->offset($this->limitOffset)
            ->limit($this->limitLength)
            ->get()
            ->toArray();

        return $articleLists;
    }

    /**
     * @notes  获取数量
     */
    public function count(): int
    {
        return Article::query()
            ->applySearchWhere($this->searchWhere) // 使用自定义宏方法
            ->count();
    }

    public function extend()
    {
        return [];
    }
}
