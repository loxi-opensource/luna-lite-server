<?php

namespace App\Console\Commands;

use App\Common\Enum\PayEnum;
use App\Common\Enum\RefundEnum;
use App\Common\Model\Recharge\RechargeOrder;
use App\Common\Model\Refund\RefundLog;
use App\Common\Model\Refund\RefundRecord;
use App\Common\Service\Pay\WeChatPayService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueryRefund extends Command
{
    protected $signature = 'app:query_refund';

    protected $description = '订单退款状态处理';

    public function handle()
    {
        try {
            $refundRecords = DB::table('refund_log as l')
                ->join('refund_record as r', 'r.id', '=', 'l.record_id')
                ->select(
                    'l.id as log_id',
                    'l.sn as log_sn',
                    'r.id as record_id',
                    'r.order_id',
                    'r.sn as record_sn',
                    'r.order_type'
                )
                ->where('l.refund_status', RefundEnum::REFUND_ING)
                ->get();

            if ($refundRecords->isEmpty()) {
                return 0; // Return 0 for success when no records are found
            }

            $rechargeRecords = $refundRecords->filter(function ($item) {
                return $item->order_type == RefundEnum::ORDER_TYPE_RECHARGE;
            });

            if (!$rechargeRecords->isEmpty()) {
                $this->handleRechargeOrder($rechargeRecords);
            }

            return 0; // Return 0 for success
        } catch (\Exception $e) {
            Log::error('订单退款状态查询失败,失败原因:' . $e->getMessage());
            return 1; // Return 1 for failure
        }
    }

    private function handleRechargeOrder($refundRecords)
    {
        $orderIds = $refundRecords->pluck('order_id')->unique();
        $orders = RechargeOrder::whereIn('id', $orderIds)->get()->keyBy('id');

        foreach ($refundRecords as $record) {
            if (!$orders->has($record->order_id)) {
                continue;
            }

            $order = $orders[$record->order_id];
            if (!in_array($order->pay_way, [PayEnum::WECHAT_PAY, PayEnum::ALI_PAY])) {
                continue;
            }

            $this->checkRefundStatus((array)$record, $order); // Cast $record to array
        }
    }


    private function checkRefundStatus(array $record, RechargeOrder $order)
    {
        $result = null;
        switch ($order->pay_way) {
            case PayEnum::WECHAT_PAY:
                $result = $this->checkWechatRefund($order->order_terminal, $record['log_sn']);
                break;
        }

        if (is_null($result)) {
            return;
        }

        if ($result === true) {
            $this->updateRefundSuccess($record['log_id'], $record['record_id']);
        } else {
            $this->updateRefundMsg($record['log_id'], $result);
        }
    }


    private function checkWechatRefund($orderTerminal, $refundLogSn)
    {
        $result = (new WeChatPayService($orderTerminal))->queryRefund($refundLogSn);

        if (isset($result['status']) && $result['status'] === 'SUCCESS') {
            return true;
        }

        if (isset($result['code']) || isset($result['message'])) {
            return '微信:' . ($result['code'] ?? '') . '-' . ($result['message'] ?? '');
        }

        return null;
    }

    private function updateRefundSuccess($logId, $recordId)
    {
        RefundLog::where('id', $logId)->update(['refund_status' => RefundEnum::REFUND_SUCCESS]);
        RefundRecord::where('id', $recordId)->update(['refund_status' => RefundEnum::REFUND_SUCCESS]);
    }


    private function updateRefundMsg($logId, $msg)
    {
        RefundLog::where('id', $logId)->update(['refund_msg' => $msg]);
    }
}
