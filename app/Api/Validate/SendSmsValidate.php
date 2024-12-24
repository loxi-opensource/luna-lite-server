<?php

namespace App\Api\Validate;

use App\Common\Validate\BaseValidate;

/**
 * 短信验证
 */
class SendSmsValidate extends BaseValidate
{

    protected $messages = [
        'mobile.required' => '请输入手机号',
        'mobile.mobile' => '请输入正确手机号',
        'scene.required' => '请输入场景值',
    ];

    public function rules($scene = '')
    {
        return [
            'mobile' => [
                'required',
                'regex:/^1[3-9]\d{9}$/',
            ],
            'scene' => 'required',
        ];
    }

}
