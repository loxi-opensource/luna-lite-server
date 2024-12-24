<?php

namespace App\Api\Validate;

use App\Common\Validate\BaseValidate;

class PasswordValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'resetPassword' => [
                'mobile' => 'required|regex:/^1[3-9]\d{9}$/',
                'code' => 'required',
                'password' => 'required|string|between:6,20|alpha_num',
                'password_confirm' => 'required|same:password',
            ],
            'changePassword' => [
                'password' => 'required|string|between:6,20|alpha_num',
                'password_confirm' => 'required|same:password',
            ],
        ];

        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'mobile.required' => '请输入手机号',
        'mobile.regex' => '请输入正确手机号',
        'code.required' => '请填写验证码',
        'password.required' => '请输入密码',
        'password.between' => '密码须在6-20位之间',
        'password.alpha_num' => '密码须为字母数字组合',
        'password_confirm.required' => '请确认密码',
        'password_confirm.same' => '两次输入的密码不一致',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
