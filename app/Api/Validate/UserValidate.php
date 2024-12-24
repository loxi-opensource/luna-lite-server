<?php

namespace App\Api\Validate;

use App\Common\Validate\BaseValidate;

/**
 * 用户验证器
 */
class UserValidate extends BaseValidate
{
    protected $messages = [
        'code.required' => '参数缺失',
        'mobile.required' => '手机号缺失',
        'mobile.regex' => '手机号格式不正确',
    ];

    public function rules($scene = '')
    {
        $rules = [
            'default' => [
                'code' => 'required',
            ],
            'getMobileByMnp' => [
                'code' => 'required',
            ],
            'bindMobile' => [
                'mobile' => [
                    'required',
                    'regex:/^1[3-9]\d{9}$/',
                ],
                'code' => 'required',
            ],
        ];

        return $rules[$scene] ?? $rules['default'];
    }
}
