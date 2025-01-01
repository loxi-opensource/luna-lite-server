<?php

namespace App\Api\Controller;

use App\Api\Validate\SwapTemplateValidate;
use App\Common\Model\SwapPageConfig;
use App\Common\Model\SwapTemplateGroup;
use Illuminate\Support\Arr;

class SwapTemplateController extends BaseApiController
{
    public array $notNeedLogin = [
        'groupList',
    ];

    // 模板组详情列表
    function groupList()
    {
        $params = (new SwapTemplateValidate())->get()->goCheck('groupList');
        $pageConfig = SwapPageConfig::findOrFail($params['page_id']);
        $pageData = $pageConfig->page_data;
        $groupIds = array_column(Arr::get($pageData, 'show_list', []), 'id');

        $rows = SwapTemplateGroup::query()
            ->with([
                'templates' =>
                    fn($query) => $query->where('status', 1)
                        ->orderByDesc('sort')
                        ->orderByDesc('id')
            ])
            ->whereIn('id', $groupIds)
            ->orderByRaw('FIND_IN_SET(id, "' . implode(',', $groupIds) . '")')
            ->orderByDesc('id')
            ->get();
        $res['groupList'] = $rows;
        return $this->success("success", $res);
    }

}
