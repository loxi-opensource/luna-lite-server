<?php

namespace App\Api\Validate;

use App\Common\Service\ConfigService;
use App\Common\Validate\BaseValidate;
use Closure;

class RechargeValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'recharge' => [
                'money' => [
                    'required',
                    'numeric',
                    'gt:0',
                    function ($attribute, $value, Closure $fail) {
                        $status = ConfigService::get('recharge', 'status', 0);
                        if (!$status) {
                            return $fail('充值功能已关闭');
                        }

                        $minAmount = ConfigService::get('recharge', 'min_amount', 0);

                        if ($value < $minAmount) {
                            return $fail('最低充值金额' . $minAmount . "元");
                        }
                    },
                ],
            ],
        ];

        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'money.required' => '请填写充值金额',
        'money.gt' => '请填写大于0的充值金额',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
