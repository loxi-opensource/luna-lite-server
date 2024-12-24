<?php

namespace App\Api\Lists\Recharge;

use App\Api\Lists\BaseApiDataLists;
use App\Common\Enum\PayEnum;
use App\Common\Model\Recharge\RechargeOrder;

/**
 * 充值记录列表
 */
class RechargeLists extends BaseApiDataLists
{
    /**
     * @notes 获取列表
     */
    public function lists(): array
    {
        $lists = RechargeOrder::query()
            ->select('order_amount', 'create_time', 'pay_way', 'pay_status')
            ->where('user_id', $this->userId)
            ->where('pay_status', PayEnum::ISPAID)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        foreach ($lists as &$item) {
            $item['tips'] = '充值' . format_amount($item['order_amount']) . '元';
        }

        return $lists;
    }

    /**
     * @notes 获取数量
     */
    public function count(): int
    {
        return RechargeOrder::query()
            ->where('user_id', $this->userId)
            ->where('pay_status', PayEnum::ISPAID)
            ->count();
    }
}
