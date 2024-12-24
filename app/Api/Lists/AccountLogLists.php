<?php

namespace App\Api\Lists;

use App\Common\Enum\User\AccountLogEnum;
use App\Common\Model\User\UserAccountLog;

/**
 * 账户流水列表
 */
class AccountLogLists extends BaseApiDataLists
{
    /**
     * @notes 搜索条件
     */
    public function queryWhere(): array
    {
        $where = [['user_id', '=', $this->userId]];

        if (isset($this->params['type']) && $this->params['type'] === 'um') {
            $where[] = ['change_type', 'in', AccountLogEnum::getUserMoneyChangeType()];
        }

        if (!empty($this->params['action'])) {
            $where[] = ['action', '=', $this->params['action']];
        }

        return $where;
    }

    /**
     * @notes 获取列表
     */
    public function lists(): array
    {
        $lists = UserAccountLog::query()
            ->select('change_type', 'change_amount', 'action', 'create_time', 'remark')
            ->applySearchWhere($this->queryWhere())
            ->orderBy('id', 'desc')
            ->limit($this->limitLength)
            ->offset($this->limitOffset)
            ->get()
            ->toArray();

        foreach ($lists as &$item) {
            $item['type_desc'] = AccountLogEnum::getChangeTypeDesc($item['change_type']);
            $symbol = $item['action'] == AccountLogEnum::DEC ? '-' : '+';
            $item['change_amount_desc'] = $symbol . $item['change_amount'];
        }

        return $lists;
    }

    /**
     * @notes 获取数量
     */
    public function count(): int
    {
        return UserAccountLog::query()->where($this->queryWhere())->count();
    }
}
