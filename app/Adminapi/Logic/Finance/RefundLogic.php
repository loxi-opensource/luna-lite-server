<?php

namespace App\Adminapi\Logic\Finance;

use App\Common\Enum\RefundEnum;
use App\Common\Logic\BaseLogic;
use App\Common\Model\Refund\RefundLog;
use App\Common\Model\Refund\RefundRecord;

/**
 * 退款
 */
class RefundLogic extends BaseLogic
{

    /**
     * @notes 退款统计
     */
    public static function stat()
    {
        $records = RefundRecord::get()->toArray();

        $total = 0;
        $ing = 0;
        $success = 0;
        $error = 0;

        foreach ($records as $record) {
            $total += $record['order_amount'];
            switch ($record['refund_status']) {
                case RefundEnum::REFUND_ING:
                    $ing += $record['order_amount'];
                    break;
                case RefundEnum::REFUND_SUCCESS:
                    $success += $record['order_amount'];
                    break;
                case RefundEnum::REFUND_ERROR:
                    $error += $record['order_amount'];
                    break;
            }
        }

        return [
            'total' => round($total, 2),
            'ing' => round($ing, 2),
            'success' => round($success, 2),
            'error' => round($error, 2),
        ];
    }

    /**
     * @notes 退款日志
     */
    public static function refundLog($recordId)
    {
        return RefundLog::query()
            ->where('record_id', $recordId)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }

}
