<?php

namespace App\Api\Lists\Article;

use App\Api\Lists\BaseApiDataLists;
use App\Common\Enum\YesNoEnum;
use App\Common\Lists\ListsSearchInterface;
use App\Common\Model\Article\Article;
use App\Common\Model\Article\ArticleCollect;

/**
 * 文章列表
 */
class ArticleLists extends BaseApiDataLists implements ListsSearchInterface
{
    /**
     * @notes 搜索条件
     */
    public function setSearch(): array
    {
        return [
            '=' => ['cid']
        ];
    }

    /**
     * @notes 自定查询条件
     * @return array
     */
    public function queryWhere()
    {
        $where[] = ['is_show', '=', 1];
        if (!empty($this->params['keyword'])) {
            $where[] = ['title', 'like', '%' . $this->params['keyword'] . '%'];
        }
        return $where;
    }


    /**
     * @notes 获取文章列表
     * @return array
     */
    public function lists(): array
    {
        $orderRaw = 'sort desc, id desc';
        $sortType = $this->params['sort'] ?? 'default';
        // 最新排序
        if ($sortType == 'new') {
            $orderRaw = 'id desc';
        }
        // 最热排序
        if ($sortType == 'hot') {
            $orderRaw = 'click_actual + click_virtual desc, id desc';
        }

        $field = 'id,cid,title,desc,image,click_virtual,click_actual,create_time';
        $result = Article::query()->select(explode(',', $field))
            ->applySearchWhere($this->queryWhere())
            ->applySearchWhere($this->searchWhere)
            ->orderByRaw($orderRaw)
            ->limit($this->limitLength)
            ->offset($this->limitOffset)
            ->get()
            ->toArray();

        $articleIds = array_column($result, 'id');

        $collectIds = ArticleCollect::query()
            ->where(['user_id' => $this->userId, 'status' => YesNoEnum::YES])
            ->whereIn('article_id', $articleIds)
            ->pluck('article_id')
            ->toArray();

        foreach ($result as &$item) {
            $item['collect'] = in_array($item['id'], $collectIds);
        }

        return $result;
    }


    /**
     * @notes 获取文章数量
     * @return int
     */
    public function count(): int
    {
        return Article::query()
            ->applySearchWhere($this->searchWhere)
            ->applySearchWhere($this->queryWhere())
            ->count();
    }
}
