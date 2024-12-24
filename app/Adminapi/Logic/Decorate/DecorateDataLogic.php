<?php

namespace App\Adminapi\Logic\Decorate;

use App\Common\Logic\BaseLogic;
use App\Common\Model\Article\Article;
use App\Common\Model\Decorate\DecoratePage;

/**
 * 装修页-数据
 */
class DecorateDataLogic extends BaseLogic
{

    /**
     * @notes 获取文章列表
     */
    public static function getArticleLists(int $limit): array
    {
        return Article::query()
            ->where('is_show', 1)
            ->select('id', 'title', 'desc', 'abstract', 'image',
                'author', 'content', 'click_virtual', 'click_actual',
                'create_time', 'cid')
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * @notes pc设置
     */
    public static function pc(): array
    {
        $pcPage = DecoratePage::findOrFail(4);
        return [
            'update_time' => $pcPage->update_time->format('Y-m-d H:i:s'),
            'pc_url' => request()->schemeAndHttpHost() . '/pc'
        ];
    }

}
