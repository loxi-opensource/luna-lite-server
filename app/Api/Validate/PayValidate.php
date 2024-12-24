<?php

namespace App\Api\Validate;

use App\Common\Enum\PayEnum;
use App\Common\Validate\BaseValidate;

class PayValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'payway' => [
                'from' => 'required',
                'order_id' => 'required',
            ],
            'status' => [
                'from' => 'required',
                'order_id' => 'required',
            ],
            'prepay' => [
                'from' => 'required',
                'pay_way' => 'required|in:' . PayEnum::BALANCE_PAY . ',' . PayEnum::WECHAT_PAY . ',' . PayEnum::ALI_PAY,
                'order_id' => 'required',
            ],
        ];

        return $rules[$scene] ?? [];
    }


    protected $messages = [
        'from.required' => '参数缺失',
        'pay_way.required' => '支付方式参数缺失',
        'pay_way.in' => '支付方式参数错误',
        'order_id.required' => '订单参数缺失',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
