<?php

namespace App\Common\Logic;

use App\Common\Enum\PayEnum;
use App\Common\Enum\YesNoEnum;
use App\Common\Model\Recharge\RechargeOrder;
use App\Common\Model\User\User;
use App\Common\Service\Pay\AliPayService;
use App\Common\Service\Pay\WechatPayService;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * 支付逻辑
 */
class PaymentLogic extends BaseLogic
{
    /**
     * @notes 支付方式
     */
    public static function getPayWay($userId, $terminal, $params): array|false
    {
        try {
            $order = null;
            if ($params['from'] == 'recharge') {
                $order = RechargeOrder::findOrFail($params['order_id']);
            }

            if (!$order) {
                throw new \Exception('待支付订单不存在');
            }

            $payWays = DB::table('dev_pay_way as pw')
                ->join('dev_pay_config as dp', 'pw.pay_config_id', '=', 'dp.id')
                ->where('pw.scene', $terminal)
                ->where('pw.status', YesNoEnum::YES)
                ->select('dp.id', 'dp.name', 'dp.pay_way', 'pw.pay_config_id', 'dp.icon', 'dp.sort', 'dp.remark', 'pw.is_default')
                ->orderByDesc('pw.is_default')
                ->orderByDesc('dp.sort')
                ->orderBy('id')
                ->get();

            $payWays = $payWays->map(function ($item) use ($userId, $params) {
                $item->extra = match ($item->pay_way) {
                    PayEnum::WECHAT_PAY => '微信快捷支付',
                    PayEnum::ALI_PAY => '支付宝快捷支付',
                    PayEnum::BALANCE_PAY => '可用余额:' . User::where('id', $userId)->value('user_money'),
                    default => '',
                };

                // 充值时去除余额支付
                if ($params['from'] == 'recharge' && $item->pay_way == PayEnum::BALANCE_PAY) {
                    return null;
                }
                return $item;
            })->filter()->values();

            return [
                'lists' => $payWays->toArray(),
                'order_amount' => $order->order_amount,
            ];

        } catch (Throwable $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 获取支付状态
     */
    public static function getPayStatus($params): array|false
    {
        try {
            $order = null;
            $orderInfo = [];

            if ($params['from'] == 'recharge') {
                $order = RechargeOrder::where('user_id', $params['user_id'])
                    ->where('id', $params['order_id'])
                    ->firstOrFail();

                $payTime = empty($order['pay_time']) ? '' : date('Y-m-d H:i:s', $order['pay_time']);

                $orderInfo = [
                    'order_id' => $order->id,
                    'order_sn' => $order->sn,
                    'order_amount' => $order->order_amount,
                    'pay_way' => PayEnum::getPayDesc($order->pay_way),
                    'pay_status' => PayEnum::getPayStatusDesc($order->pay_status),
                    'pay_time' => $payTime,
                ];
            }

            if (!$order) {
                throw new \Exception('订单不存在');
            }

            return [
                'pay_status' => $order->pay_status,
                'pay_way' => $order->pay_way,
                'order' => $orderInfo,
            ];

        } catch (Throwable $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 获取预支付订单信息
     */
    public static function getPayOrderInfo($params): RechargeOrder|false
    {
        try {
            if ($params['from'] == 'recharge') {
                $order = RechargeOrder::findOrFail($params['order_id']);
                if ($order->pay_status == PayEnum::ISPAID) {
                    throw new \Exception('订单已支付');
                }
                return $order;
            }

            return false;
        } catch (Throwable $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * @notes 支付
     */
    public static function pay($payWay, $from, $order, $terminal, $redirectUrl): mixed
    {
        $paySn = $order->sn;
        if ($payWay == PayEnum::WECHAT_PAY) {
            $paySn = self::formatOrderSn($order->sn, $terminal);
        }


        if ($from == 'recharge') {
            RechargeOrder::where('id', $order->id)->update(['pay_way' => $payWay, 'pay_sn' => $paySn]);
        }


        if ($order->order_amount == 0) {
            PayNotifyLogic::handle($from, $order->sn);
            return ['pay_way' => PayEnum::BALANCE_PAY];
        }

        $payService = match ($payWay) {
            PayEnum::WECHAT_PAY => new WechatPayService($terminal, $order->user_id),
            PayEnum::ALI_PAY => new AliPayService($terminal),
            default => throw new \Exception('Unsupported payment method'),
        };

        $order->pay_sn = $paySn;
        $order->redirect_url = $redirectUrl;


        $result = $payService->pay($from, $order);


        if ($result === false && !self::hasError()) {
            self::setError($payService->getError());
        }

        return $result;
    }


    /**
     * @notes 设置订单号 支付回调时截取前面的单号 18个
     */
    public static function formatOrderSn($orderSn, $terminal): string
    {
        $suffix = substr(strval(time()), -4);
        return $orderSn . $terminal . $suffix;
    }
}
