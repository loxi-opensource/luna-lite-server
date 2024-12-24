<?php

namespace App\Api\Lists\Article;

use App\Api\Lists\BaseApiDataLists;
use App\Common\Enum\YesNoEnum;
use App\Common\Service\FileService;
use Illuminate\Support\Facades\DB;

/**
 * 文章收藏列表
 */
class ArticleCollectLists extends BaseApiDataLists
{
    /**
     * @notes 获取收藏列表
     * @return array
     */
    public function lists(): array
    {
        $field = [
            'c.id', 'c.article_id', 'a.title', 'a.image', 'a.desc', 'a.is_show',
            'a.click_virtual', 'a.click_actual', 'a.create_time', 'a.cid',
            DB::raw(env('DB_PREFIX', '') . 'c.create_time as collect_time'),
        ];

        $lists = DB::table('article as a')
            ->join('article_collect as c', 'c.article_id', '=', 'a.id')
            ->select($field)
            ->where([
                'c.user_id' => $this->userId,
                'c.status' => YesNoEnum::YES,
                'a.is_show' => YesNoEnum::YES,
            ])
            ->orderByDesc('sort')
            ->orderByDesc('c.id')
            ->offset($this->limitOffset)
            ->limit($this->limitLength)
            ->get()
            ->map(function ($item) {
                $item->collect_time = date('Y-m-d H:i', $item->collect_time);
                $item->create_time = date('Y-m-d H:i', $item->create_time);
                $item->click = $item->click_virtual + $item->click_actual;
                unset($item->click_virtual, $item->click_actual);
                $item->image = FileService::getFileUrl($item->image);
                return $item;
            })
            ->toArray();

        return $lists;
    }

    /**
     * @notes 获取收藏数量
     * @return int
     */
    public function count(): int
    {
        return DB::table('article as a')
            ->join('article_collect as c', 'c.article_id', '=', 'a.id')
            ->where([
                'c.user_id' => $this->userId,
                'c.status' => YesNoEnum::YES,
                'a.is_show' => YesNoEnum::YES,
            ])
            ->count();
    }
}
