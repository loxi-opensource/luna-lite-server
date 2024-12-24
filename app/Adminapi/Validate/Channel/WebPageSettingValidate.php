<?php

namespace App\Adminapi\Validate\Channel;

use App\Common\Validate\BaseValidate;

/**
 * H5设置验证器
 */
class WebPageSettingValidate extends BaseValidate
{
    protected $messages = [
        'status.required' => '请选择启用状态',
        'status.in' => '启用状态值有误',
    ];

    public function rules($scene = '')
    {
        // 定义基本的验证规则
        $rules = [
            'status' => 'required|in:0,1',
        ];

        // 定义场景
        $scenes = [
            'default' => ['status'], // 默认场景
        ];

        // 根据场景返回相应的规则
        if (isset($scenes[$scene])) {
            return array_intersect_key($rules, array_flip($scenes[$scene]));
        }

        return $rules; // 默认返回所有规则
    }
}
