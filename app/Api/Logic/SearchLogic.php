<?php

namespace App\Api\Logic;

use App\Common\Logic\BaseLogic;
use App\Common\Model\HotSearch;
use App\Common\Service\ConfigService;

/**
 * 搜索逻辑
 */
class SearchLogic extends BaseLogic
{

    /**
     * @notes 热搜列表
     */
    public static function hotLists()
    {
        $data = HotSearch::select(['name', 'sort'])
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->get()->toArray();

        return [
            // 功能状态 0-关闭 1-开启
            'status' => ConfigService::get('hot_search', 'status', 0),
            // 热门搜索数据
            'data' => $data,
        ];
    }

}
