<?php

namespace App\Adminapi\Logic\Recharge;

use App\Common\Enum\RefundEnum;
use App\Common\Enum\User\AccountLogEnum;
use App\Common\Enum\YesNoEnum;
use App\Common\Logic\AccountLogLogic;
use App\Common\Logic\BaseLogic;
use App\Common\Logic\RefundLogic;
use App\Common\Model\Recharge\RechargeOrder;
use App\Common\Model\Refund\RefundRecord;
use App\Common\Model\User\User;
use App\Common\Service\ConfigService;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * 充值逻辑层
 */
class RechargeLogic extends BaseLogic
{

    /**
     * @notes 获取充值设置
     */
    public static function getConfig()
    {
        $config = [
            'status' => ConfigService::get('recharge', 'status', 0),
            'min_amount' => ConfigService::get('recharge', 'min_amount', 0)
        ];

        return $config;
    }


    /**
     * @notes 充值设置
     */
    public static function setConfig($params)
    {
        try {
            if (isset($params['status'])) {
                ConfigService::set('recharge', 'status', $params['status']);
            }
            if (isset($params['min_amount'])) {
                ConfigService::set('recharge', 'min_amount', $params['min_amount']);
            }
            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 退款
     */
    public static function refund($params, $adminId)
    {
        try {
            DB::beginTransaction();

            $order = RechargeOrder::findOrFail($params['recharge_id']);

            // 更新订单信息, 标记已发起退款状态,具体退款成功看退款日志
            $order->update([
                'refund_status' => YesNoEnum::YES,
            ]);

            // 更新用户余额及累计充值金额
            User::query()->where('id', $order->user_id)
                ->decrement('total_recharge_amount', $order->order_amount);
            User::query()->where('id', $order->user_id)
                ->decrement('user_money', $order->order_amount);

            // 记录日志
            AccountLogLogic::add(
                $order->user_id,
                AccountLogEnum::UM_DEC_RECHARGE_REFUND,
                AccountLogEnum::DEC,
                $order->order_amount,
                $order->sn,
                '充值订单退款'
            );

            // 生成退款记录
            $recordSn = generate_sn(RefundRecord::class, 'sn');
            $record = RefundRecord::create([
                'sn' => $recordSn,
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'order_sn' => $order->sn,
                'order_type' => RefundEnum::ORDER_TYPE_RECHARGE,
                'order_amount' => $order->order_amount,
                'refund_amount' => $order->order_amount,
                'refund_type' => RefundEnum::TYPE_ADMIN,
                'transaction_id' => $order->transaction_id ?? '',
                'refund_way' => RefundEnum::getRefundWayByPayWay($order->pay_way),
            ]);

            // 退款
            $result = RefundLogic::refund($order, $record->id, $order->order_amount, $adminId);

            $flag = true;
            $resultMsg = '操作成功';
            if ($result !== true) {
                $flag = false;
                $resultMsg = RefundLogic::getError();
            }

            DB::commit();
            return [$flag, $resultMsg];
        } catch (Throwable $e) {
            DB::rollBack();
            self::$error = $e->getMessage();
            return [false, $e->getMessage()];
        }
    }


    /**
     * @notes 重新退款
     */
    public static function refundAgain($params, $adminId)
    {
        try {
            DB::beginTransaction();

            $record = RefundRecord::findOrFail($params['record_id']);
            $order = RechargeOrder::findOrFail($record->order_id);

            // 退款
            $result = RefundLogic::refund($order, $record->id, $order->order_amount, $adminId);

            $flag = true;
            $resultMsg = '操作成功';
            if ($result !== true) {
                $flag = false;
                $resultMsg = RefundLogic::getError();
            }

            DB::commit();
            return [$flag, $resultMsg];
        } catch (Throwable $e) {
            DB::rollBack();
            self::$error = $e->getMessage();
            return [false, $e->getMessage()];
        }
    }

}
