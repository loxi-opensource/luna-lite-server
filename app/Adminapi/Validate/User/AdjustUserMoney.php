<?php

namespace App\Adminapi\Validate\User;

use App\Common\Enum\User\AccountLogEnum;
use App\Common\Model\User\User;
use App\Common\Validate\BaseValidate;
use Closure;

class AdjustUserMoney extends BaseValidate
{
    public function rules($scene = '')
    {
        return [
            'user_id' => 'required|exists:user,id',
            'action' => 'required|in:' . AccountLogEnum::INC . ',' . AccountLogEnum::DEC,
            'num' => [
                'required',
                'numeric',
                'gt:0',
                function ($attribute, $value, Closure $fail) {
                    $user = User::find(request()->user_id);
                    if (!$user) {
                        return $fail('用户不存在');
                    }

                    if (request()->action == 1) {
                        return;
                    }

                    $surplusMoney = $user->user_money - $value;
                    if ($surplusMoney < 0) {
                        return $fail('用户可用余额仅剩' . $user->user_money);
                    }
                },
            ],
            'remark' => 'nullable|string|max:128',
        ];
    }

    protected $messages = [
        'user_id.required' => '请选择用户',
        'user_id.exists' => '用户不存在',
        'action.required' => '请选择调整类型',
        'action.in' => '调整类型错误',
        'num.required' => '请输入调整数量',
        'num.gt' => '调整余额必须大于零',
        'remark.max' => '备注不可超过128个符号',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
