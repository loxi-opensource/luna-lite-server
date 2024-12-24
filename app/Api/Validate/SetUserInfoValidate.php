<?php

namespace App\Api\Validate;

use App\Common\Model\User\User;
use App\Common\Validate\BaseValidate;
use Closure;

class SetUserInfoValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        return [
            'field' => [
                'required',
                function ($attribute, $value, Closure $fail) {
                    $allowField = ['nickname', 'account', 'sex', 'avatar', 'real_name'];
                    if (!in_array($value, $allowField)) {
                        return $fail('参数错误');
                    }
                },
            ],
            'value' => [
                'required',
                function ($attribute, $value, Closure $fail) {
                    if (request()->field == 'account') {
                        $user = User::where('account', $value)
                            ->where('id', '<>', self::getParam('user_id'))
                            ->first();
                        if ($user) {
                            return $fail('账号已被使用!');
                        }
                    }
                },
            ],
        ];
    }

    protected $messages = [
        'field.required' => '参数缺失',
        'value.required' => '值不存在',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
