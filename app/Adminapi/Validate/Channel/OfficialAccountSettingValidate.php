<?php

namespace App\Adminapi\Validate\Channel;

use App\Common\Validate\BaseValidate;

/**
 * 公众号设置
 */
class OfficialAccountSettingValidate extends BaseValidate
{
    protected $messages = [
        'app_id.required' => '请填写AppID',
        'app_secret.required' => '请填写AppSecret',
        'encryption_type.required' => '请选择消息加密方式',
        'encryption_type.in' => '消息加密方式状态值错误',
    ];

    public function rules($scene = '')
    {
        // 定义基本的验证规则
        $rules = [
            'app_id' => 'required',
            'app_secret' => 'required',
            'encryption_type' => 'required|in:1,2,3',
        ];

        // 定义场景
        $scenes = [
            'default' => ['app_id', 'app_secret', 'encryption_type'],
            // 可以在这里添加更多场景
        ];

        // 根据场景返回相应的规则
        if (isset($scenes[$scene])) {
            return array_intersect_key($rules, array_flip($scenes[$scene]));
        }

        return $rules; // 默认返回所有规则
    }

}
