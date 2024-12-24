<?php

namespace App\Common\Logic;

use App\Common\Enum\PayEnum;
use App\Common\Enum\User\AccountLogEnum;
use App\Common\Model\Recharge\RechargeOrder;
use App\Common\Model\User\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * 支付成功后处理订单状态
 */
class PayNotifyLogic extends BaseLogic
{
    public static function handle(string $action, string $orderSn, array $extra = []): bool|string
    {
        try {
            DB::beginTransaction();
            self::{$action}($orderSn, $extra);
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('回调请求处理异常', [
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
            self::setError($e->getMessage());
            return $e->getMessage();
        }
    }

    /**
     * @notes 充值回调
     */
    public static function recharge(string $orderSn, array $extra = [])
    {
        $order = RechargeOrder::where('sn', $orderSn)->firstOrFail();
        $user = User::findOrFail($order->user_id);

        $user->increment('total_recharge_amount', $order->order_amount);
        $user->increment('user_money', $order->order_amount);

        AccountLogLogic::add(
            $order->user_id,
            AccountLogEnum::UM_INC_RECHARGE,
            AccountLogEnum::INC,
            $order->order_amount,
            $order->sn,
            '用户充值'
        );

        $order->transaction_id = $extra['transaction_id'] ?? '';
        $order->pay_status = PayEnum::ISPAID;
        $order->pay_time = time();
        $order->save();
    }
}

