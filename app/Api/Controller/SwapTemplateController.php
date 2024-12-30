<?php

namespace App\Api\Controller;

use App\Api\Validate\SwapTemplateValidate;
use App\Common\Model\SwapTemplateGroup;

class SwapTemplateController extends BaseApiController
{
    public array $notNeedLogin = [
        'groupList',
    ];

    // 模板组详情列表
    function groupList()
    {
        $params = (new SwapTemplateValidate())->get()->goCheck('groupList');
        $rows = SwapTemplateGroup::query()
            ->with(['templates' => fn($query) => $query->where('status', 1)])
            ->orderByDesc('id')
            ->get();
        $res['groupList'] = $rows;
        return $this->success("success", $res);
    }

}
