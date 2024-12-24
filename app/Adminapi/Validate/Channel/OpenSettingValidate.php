<?php

namespace App\Adminapi\Validate\Channel;

use App\Common\Validate\BaseValidate;

/**
 * 开放平台验证
 */
class OpenSettingValidate extends BaseValidate
{
    protected $messages = [
        'app_id.required' => '请输入appId',
        'app_secret.required' => '请输入appSecret',
    ];

    public function rules($scene = '')
    {
        // 定义基本的验证规则
        $rules = [
            'app_id' => 'required',
            'app_secret' => 'required',
        ];

        // 定义场景
        $scenes = [
            'default' => ['app_id', 'app_secret'], // 默认场景
            // 可以在这里添加更多场景
        ];

        // 根据场景返回相应的规则
        if (isset($scenes[$scene])) {
            return array_intersect_key($rules, array_flip($scenes[$scene]));
        }

        return $rules; // 默认返回所有规则
    }
}
