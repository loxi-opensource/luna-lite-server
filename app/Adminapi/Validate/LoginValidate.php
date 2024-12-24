<?php

namespace App\Adminapi\Validate;

use App\Adminapi\Rule\VerifyAdminPassword;
use App\Common\Enum\AdminTerminalEnum;
use App\Common\Validate\BaseValidate;

class LoginValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'account' => [
                'terminal' => 'required|in:' . AdminTerminalEnum::PC . ',' . AdminTerminalEnum::MOBILE,
                'account' => 'required',
                'password' => ['required', new VerifyAdminPassword],
            ],
        ];
        return $rules[$scene] ?? [];
    }

    public function messages()
    {
        return [
            'account.required' => '请输入账号',
            'password.required' => '请输入密码',
            'terminal.required' => '终端类型不能为空',
            'terminal.in' => '终端类型错误',
        ];
    }

}
