<?php

namespace App\Adminapi\Validate\User;

use App\Common\Model\User\User;
use App\Common\Validate\BaseValidate;
use Closure;

class UserValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'detail' => [
                'id' => 'required|exists:user,id',
            ],
            'setInfo' => [
                'id' => 'required|exists:user,id',
                'field' => [
                    'required',
                    function ($attribute, $value, Closure $fail) {
                        $allowField = ['account', 'sex', 'mobile', 'real_name'];
                        if (!in_array($value, $allowField)) {
                            return $fail('用户信息不允许更新');
                        }
                    },
                ],
                'value' => [
                    'required',
                    function ($attribute, $value, Closure $fail) {
                        switch (request()->field) {
                            case 'account':
                                $account = User::where('id', '<>', request()->id)
                                    ->where('account', $value)
                                    ->first();
                                if ($account) {
                                    return $fail('账号已被使用');
                                }
                                break;
                            case 'mobile':
                                if (!preg_match('/^1[3-9]\d{9}$/', $value)) {
                                    return $fail('手机号码格式错误');
                                }

                                $mobile = User::where('id', '<>', request()->id)
                                    ->where('mobile', $value)
                                    ->first();
                                if ($mobile) {
                                    return $fail('手机号码已存在');
                                }
                                break;
                        }
                    },
                ],
            ],
        ];

        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'id.required' => '请选择用户',
        'field.required' => '请选择操作',
        'value.required' => '请输入内容',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
