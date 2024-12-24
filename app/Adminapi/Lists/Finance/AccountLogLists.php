<?php

namespace App\Adminapi\Lists\Finance;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Enum\User\AccountLogEnum;
use App\Common\Lists\ListsSearchInterface;
use App\Common\Service\FileService;
use Illuminate\Support\Facades\DB;

/**
 * 账记流水列表
 */
class AccountLogLists extends BaseAdminDataLists implements ListsSearchInterface
{

    /**
     * @notes 搜索条件
     */
    public function setSearch(): array
    {
        return [
            '=' => ['al.change_type'],
        ];
    }


    /**
     * @notes 搜索条件
     * @author 段誉
     * @date 2023/2/24 15:26
     */
    public function queryWhere()
    {
        $where = [];
        // 用户余额
        if (isset($this->params['type']) && $this->params['type'] == 'um') {
            $where[] = ['change_type', 'in', AccountLogEnum::getUserMoneyChangeType()];
        }

        if (!empty($this->params['start_time'])) {
            $where[] = ['al.create_time', '>=', strtotime($this->params['start_time'])];
        }

        if (!empty($this->params['end_time'])) {
            $where[] = ['al.create_time', '<=', strtotime($this->params['end_time'])];
        }

        return $where;
    }

    private function createBaseQuery()
    {
        $baseQuery = DB::table('user_account_log as al')
            ->join('user as u', 'u.id', '=', 'al.user_id')
            ->applySearchWhere($this->searchWhere)
            ->applySearchWhere($this->queryWhere());

        if (!empty($this->params['user_info'])) {
            $baseQuery->where(function ($q) {
                $q->where('u.sn', 'like', '%' . $this->params['user_info'] . '%')
                    ->orWhere('u.nickname', 'like', '%' . $this->params['user_info'] . '%')
                    ->orWhere('u.mobile', 'like', '%' . $this->params['user_info'] . '%')
                    ->orWhere('u.account', 'like', '%' . $this->params['user_info'] . '%');
            });
        }

        return $baseQuery;
    }

    /**
     * @notes 获取列表
     */
    public function lists(): array
    {
        $lists = $this->createBaseQuery()
            ->select(
                'u.nickname', 'u.account', 'u.sn', 'u.avatar', 'u.mobile',
                'al.action', 'al.change_amount', 'al.left_amount', 'al.change_type',
                'al.source_sn', 'al.create_time'
            )
            ->orderBy('al.id', 'desc')
            ->limit($this->limitLength)
            ->offset($this->limitOffset)
            ->get();

        return $lists->map(function ($item) {
            $item->avatar = FileService::getFileUrl($item->avatar);
            $item->change_type_desc = AccountLogEnum::getChangeTypeDesc($item->change_type);
            $symbol = $item->action == AccountLogEnum::INC ? '+' : '-';
            $item->change_amount = $symbol . $item->change_amount;
            $item->create_time = date('Y-m-d H:i:s', $item->create_time);
            return $item;
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
