<?php

namespace App\Adminapi\Lists\Setting\Pay;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Model\Pay\PayConfig;

/**
 * 支付配置列表
 */
class PayConfigLists extends BaseAdminDataLists
{
    /**
     * @notes 获取列表
     */
    public function lists(): array
    {
        return PayConfig::query()
            ->select('id', 'name', 'pay_way', 'icon', 'sort')
            ->orderBy('sort', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * @notes 获取数量
     */
    public function count(): int
    {
        return PayConfig::count();
    }
}
