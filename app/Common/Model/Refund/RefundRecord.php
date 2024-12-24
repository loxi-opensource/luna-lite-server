<?php

namespace App\Common\Model\Refund;

use App\Common\Enum\RefundEnum;
use App\Common\Model\BaseModel;

/**
 * 退款记录模型
 */
class RefundRecord extends BaseModel
{
    protected $table = 'refund_record';

    protected $appends = ['refund_type_text', 'refund_status_text', 'refund_way_text'];

    /**
     * @notes 退款类型描述
     */
    public function getRefundTypeTextAttribute($value)
    {
        return RefundEnum::getTypeDesc($this->attributes['refund_type']);
    }


    /**
     * @notes 退款状态描述
     */
    public function getRefundStatusTextAttribute($value)
    {
        return RefundEnum::getStatusDesc($this->attributes['refund_status']);
    }


    /**
     * @notes 退款方式描述
     */
    public function getRefundWayTextAttribute($value)
    {
        return RefundEnum::getWayDesc($this->attributes['refund_way']);
    }

}
