<?php

namespace App\Api\Controller;

use App\Api\Logic\SearchLogic;

/**
 * 搜索
 */
class SearchController extends BaseApiController
{

    public array $notNeedLogin = ['hotLists'];

    /**
     * @notes 热门搜素
     */
    public function hotLists()
    {
        return $this->data(SearchLogic::hotLists());
    }

}
