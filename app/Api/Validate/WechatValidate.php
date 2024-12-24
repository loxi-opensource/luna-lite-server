<?php

namespace App\Api\Validate;

use App\Common\Validate\BaseValidate;

/**
 * 微信验证器
 */
class WechatValidate extends BaseValidate
{
    protected $messages = [
        'url.required' => '请提供url',
    ];

    public function rules($scene = '')
    {
        $rules = [
            'default' => [
                'url' => 'required',
            ],
            'jsConfig' => [
                'url' => 'required',
            ],
        ];

        return $rules[$scene] ?? $rules['default'];
    }
}
