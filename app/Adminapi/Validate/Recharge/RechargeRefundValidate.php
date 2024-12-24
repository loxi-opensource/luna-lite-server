<?php

namespace App\Adminapi\Validate\Recharge;

use App\Common\Enum\PayEnum;
use App\Common\Enum\RefundEnum;
use App\Common\Enum\YesNoEnum;
use App\Common\Model\Recharge\RechargeOrder;
use App\Common\Model\Refund\RefundRecord;
use App\Common\Model\User\User;
use App\Common\Validate\BaseValidate;
use Closure;

class RechargeRefundValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'refund' => [
                'recharge_id' => [
                    'required',
                    function ($attribute, $value, Closure $fail) {
                        $order = RechargeOrder::find($value);
                        if (!$order) {
                            return $fail('充值订单不存在');
                        }

                        if ($order->pay_status != PayEnum::ISPAID) {
                            return $fail('当前订单不可退款');
                        }

                        if ($order->refund_status == YesNoEnum::YES) {
                            return $fail('订单已发起退款,退款失败请到退款记录重新退款');
                        }

                        $user = User::find($order->user_id);
                        if ($user && $user->user_money < $order->order_amount) {
                            return $fail('退款失败:用户余额已不足退款金额');
                        }
                    },
                ],
            ],
            'again' => [
                'record_id' => [
                    'required',
                    function ($attribute, $value, Closure $fail) {
                        $record = RefundRecord::find($value);
                        if (!$record) {
                            return $fail('退款记录不存在');
                        }

                        if ($record->refund_status == RefundEnum::REFUND_SUCCESS) {
                            return $fail('该退款记录已退款成功');
                        }

                        $order = RechargeOrder::find($record->order_id);
                        $user = User::find($record->user_id);

                        if ($user && $user->user_money < $order->order_amount) {
                            return $fail('退款失败:用户余额已不足退款金额');
                        }
                    },
                ],
            ],
        ];

        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'recharge_id.required' => '参数缺失',
        'record_id.required' => '参数缺失',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
