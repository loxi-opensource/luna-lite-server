<?php

namespace App\Common\Model\Recharge;

use App\Common\Enum\PayEnum;
use App\Common\Model\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 充值订单模型
 */
class RechargeOrder extends BaseModel
{
    use SoftDeletes;

    protected $table = 'recharge_order';

    protected $appends = ['pay_way_text', 'pay_status_text'];

    public $casts = [
        'recharge_package_snapshot' => 'array'
    ];

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

    /**
     * @notes 支付方式
     */
    public function getPayWayTextAttribute($value)
    {
        return PayEnum::getPayDesc($this->attributes['pay_way']);
    }

    /**
     * @notes 支付状态
     */
    public function getPayStatusTextAttribute($value)
    {
        return PayEnum::getPayStatusDesc($this->attributes['pay_status']);
    }
}
