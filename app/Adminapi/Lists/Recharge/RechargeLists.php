<?php

namespace App\Adminapi\Lists\Recharge;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Enum\PayEnum;
use App\Common\Lists\ListsExcelInterface;
use App\Common\Lists\ListsSearchInterface;
use App\Common\Service\FileService;
use Illuminate\Support\Facades\DB;

/**
 * 充值记录列表
 */
class RechargeLists extends BaseAdminDataLists implements ListsSearchInterface, ListsExcelInterface
{
    /**
     * @notes 导出字段
     */
    public function setExcelFields(): array
    {
        return [
            'sn' => '充值单号',
            'nickname' => '用户昵称',
            'order_amount' => '充值金额',
            'pay_way_text' => '支付方式',
            'pay_status_text' => '支付状态',
            'pay_time' => '支付时间',
            'create_time' => '下单时间',
        ];
    }

    /**
     * @notes 导出表名
     */
    public function setFileName(): string
    {
        return '充值记录';
    }


    /**
     * @notes 搜索条件
     */
    public function setSearch(): array
    {
        return [
            '=' => ['ro.sn', 'ro.pay_way', 'ro.pay_status'],
        ];
    }

    private function getParamByKey($key)
    {
        return $this->params[$key] ?? '';
    }

    private function createBaseQuery()
    {
        $query = DB::table('recharge_order as ro')
            ->join('user as u', 'u.id', '=', 'ro.user_id')
            ->applySearchWhere($this->searchWhere);

        if (!empty($this->getParamByKey('user_info'))) {
            $query->where(function ($q) {
                $q->where('u.sn', 'like', '%' . $this->getParamByKey('user_info') . '%')
                    ->orWhere('u.nickname', 'like', '%' . $this->getParamByKey('user_info') . '%')
                    ->orWhere('u.mobile', 'like', '%' . $this->getParamByKey('user_info') . '%')
                    ->orWhere('u.account', 'like', '%' . $this->getParamByKey('user_info') . '%');
            });
        }

        if (!empty($this->getParamByKey('start_time')) && !empty($this->getParamByKey('end_time'))) {
            $query->whereBetween('ro.create_time', [strtotime($this->getParamByKey('start_time')), strtotime($this->getParamByKey('end_time'))]);
        }

        return $query;
    }

    public function lists(): array
    {
        $lists = $this->createBaseQuery()
            ->select(
                'ro.id', 'ro.sn', 'ro.order_amount', 'ro.pay_way', 'ro.pay_time',
                'ro.pay_status', 'ro.create_time', 'ro.refund_status',
                'u.avatar', 'u.nickname', 'u.account'
            )
            ->orderBy('ro.id', 'desc')
            ->limit($this->limitLength)
            ->offset($this->limitOffset)
            ->get();

        return $lists->map(function ($item) {
            $item->avatar = FileService::getFileUrl($item->avatar);
            $item->pay_time = $item->pay_time ? date('Y-m-d H:i:s', $item->pay_time) : '';
            $item->create_time = date('Y-m-d H:i:s', $item->create_time);
            $item->pay_status_text = PayEnum::getPayStatusDesc($item->pay_status);
            $item->pay_way_text = PayEnum::getPayDesc($item->pay_way);
            return (array)$item;
        })->toArray();
    }


    /**
     * @notes 获取数量
     */
    public function count(): int
    {
        return $this->createBaseQuery()->count();
    }


}
