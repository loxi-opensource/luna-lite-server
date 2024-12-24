<?php

namespace App\Api\Validate;

use App\Common\Validate\BaseValidate;

/**
 * 注册验证器
 */
class RegisterValidate extends BaseValidate
{
    const REGEX_REGISTER = '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+/';
    const REGEX_PASSWORD = '/^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)(?!([^(0-9a-zA-Z)]|[\(\)])+$)([^(0-9a-zA-Z)]|[\(\)]|[a-z]|[A-Z]|[0-9]){6,20}$/';

    protected $messages = [
        'channel.required' => '注册来源参数缺失',
        'account.required' => '请输入账号',
        'account.regex' => '账号须为字母数字组合',
        'account.between' => '账号须为3-12位之间',
        'account.unique' => '账号已存在',
        'password.required' => '请输入密码',
        'password.between' => '密码须在6-20位之间',
        'password.regex' => '密码须为数字,字母或符号组合',
        'password_confirm.required' => '请确认密码',
        'password_confirm.same' => '两次输入的密码不一致'
    ];

    public function rules($scene = 'default')
    {
        return [
            'channel' => 'required',
            'account' => [
                'required',
                'string',
                'between:3,12',
                'unique:user,account',
                'regex:' . self::REGEX_REGISTER
            ],
            'password' => [
                'required',
                'string',
                'between:6,20',
                'regex:' . self::REGEX_PASSWORD
            ],
            'password_confirm' => 'required|same:password',
        ];
    }

}
