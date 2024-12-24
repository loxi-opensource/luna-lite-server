<?php

namespace App\Adminapi\Lists\Finance;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Enum\RefundEnum;
use App\Common\Lists\ListsExtendInterface;
use App\Common\Lists\ListsSearchInterface;
use App\Common\Service\FileService;
use Illuminate\Support\Facades\DB;

/**
 * 退款记录列表
 */
class RefundRecordLists extends BaseAdminDataLists implements ListsSearchInterface, ListsExtendInterface
{
    public function setSearch(): array
    {
        return [
            '=' => ['r.sn', 'r.order_sn', 'r.refund_type'],
        ];
    }

    private function createBaseQuery($applyRefundStatusWhere = true)
    {
        $query = DB::table('refund_record as r')
            ->join('user as u', 'u.id', '=', 'r.user_id')
            ->applySearchWhere($this->searchWhere);

        if (!empty($this->params['user_info'])) {
            $query->where(function ($q) {
                $q->where('u.sn', 'like', '%' . $this->params['user_info'] . '%')
                    ->orWhere('u.nickname', 'like', '%' . $this->params['user_info'] . '%')
                    ->orWhere('u.mobile', 'like', '%' . $this->params['user_info'] . '%')
                    ->orWhere('u.account', 'like', '%' . $this->params['user_info'] . '%');
            });
        }

        if (!empty($this->params['start_time'])) {
            $query->where('r.create_time', '>=', strtotime($this->params['start_time']));
        }

        if (!empty($this->params['end_time'])) {
            $query->where('r.create_time', '<=', strtotime($this->params['end_time']));
        }

        if ($applyRefundStatusWhere && isset($this->params['refund_status']) && $this->params['refund_status'] !== '') {
            $query->where('r.refund_status', $this->params['refund_status']);
        }

        return $query;
    }


    public function lists(): array
    {
        $lists = $this->createBaseQuery()
            ->select('r.*', 'u.nickname', 'u.avatar')
            ->orderBy('r.id', 'desc')
            ->limit($this->limitLength)
            ->offset($this->limitOffset)
            ->get();

        return $lists->map(function ($item) {
            $item->avatar = FileService::getFileUrl($item->avatar);
            $item->refund_type_text = RefundEnum::getTypeDesc($item->refund_type);
            $item->refund_status_text = RefundEnum::getStatusDesc($item->refund_status);
            $item->refund_way_text = RefundEnum::getWayDesc($item->refund_way);
            return (array)$item;
        })->toArray();
    }

    public function count(): int
    {
        return $this->createBaseQuery()->count();
    }


    public function extend()
    {
        $prefix = DB::getTablePrefix();

        $counts = $this->createBaseQuery(false)
            ->selectRaw("
                 count({$prefix}r.id) as total,
                sum(case when {$prefix}r.refund_status = ? then 1 else 0 end) as ing,
                sum(case when {$prefix}r.refund_status = ? then 1 else 0 end) as success,
                sum(case when {$prefix}r.refund_status = ? then 1 else 0 end) as error
            ", [RefundEnum::REFUND_ING, RefundEnum::REFUND_SUCCESS, RefundEnum::REFUND_ERROR])
            ->get();

        return (array)$counts[0];
    }
}
