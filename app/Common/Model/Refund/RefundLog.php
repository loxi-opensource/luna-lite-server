<?php

namespace App\Common\Model\Refund;

use App\Common\Enum\RefundEnum;
use App\Common\Model\Auth\Admin;
use App\Common\Model\BaseModel;

/**
 * 退款日志模型
 */
class RefundLog extends BaseModel
{
    protected $table = 'refund_log';

    protected $appends = ['refund_status_text', 'handler'];

    /**
     * @notes 操作人描述
     */
    public function getHandlerAttribute($value)
    {
        return Admin::query()->where('id', $this->attributes['handle_id'])->value('name');
    }


    /**
     * @notes 退款状态描述
     */
    public function getRefundStatusTextAttribute($value)
    {
        return RefundEnum::getStatusDesc($this->attributes['refund_status']);
    }

}
