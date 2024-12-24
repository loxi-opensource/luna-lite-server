<?php

namespace App\Adminapi\Lists\User;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Enum\User\UserTerminalEnum;
use App\Common\Lists\ListsExcelInterface;
use App\Common\Model\User\User;
use Illuminate\Support\Arr;

/**
 * 用户列表
 */
class UserLists extends BaseAdminDataLists implements ListsExcelInterface
{
    /**
     * @notes 搜索条件
     */
    public function queryWhere(): array
    {
        $where = [];
        if (Arr::get($this->params, 'channel')) {
            $where[] = ['channel', '=', $this->params['channel']];
        }
        if (Arr::get($this->params, 'create_time_start')) {
            $where[] = ['create_time', '>=', strtotime($this->params['create_time_start'])];
        }
        if (Arr::get($this->params, 'create_time_end')) {
            $where[] = ['create_time', '<=', strtotime($this->params['create_time_end'])];
        }
        return $where;
    }

    /**
     * @notes 获取用户列表
     */
    public function lists(): array
    {
        $fields = ['id', 'sn', 'nickname', 'sex', 'avatar', 'account', 'mobile', 'channel', 'create_time'];
        $lists = $this->createBaseQuery()
            ->select($fields)
            ->orderBy('id', 'desc')
            ->offset($this->limitOffset)
            ->limit($this->limitLength)
            ->get()
            ->toArray();

        foreach ($lists as &$item) {
            $item['channel'] = UserTerminalEnum::getTermInalDesc($item['channel']);
        }

        return $lists;
    }


    /**
     * @notes 获取数量
     */
    public function count(): int
    {
        return $this->createBaseQuery()->count();
    }

    private function createBaseQuery()
    {
        $baseQuery = User::query()->applySearchWhere($this->queryWhere());
        if (isset($this->params['keyword']) && $this->params['keyword']) {
            $baseQuery->where(function ($query) {
                $query->where('sn', 'like', '%' . $this->params['keyword'] . '%')
                    ->orWhere('nickname', 'like', '%' . $this->params['keyword'] . '%')
                    ->orWhere('mobile', 'like', '%' . $this->params['keyword'] . '%')
                    ->orWhere('account', 'like', '%' . $this->params['keyword'] . '%');
            });
        }
        return $baseQuery;
    }

    /**
     * @notes 导出文件名
     */
    public function setFileName(): string
    {
        return '用户列表';
    }


    /**
     * @notes 导出字段
     */
    public function setExcelFields(): array
    {
        return [
            'sn' => '用户编号',
            'nickname' => '用户昵称',
            'account' => '账号',
            'mobile' => '手机号码',
            'channel' => '注册来源',
            'create_time' => '注册时间',
        ];
    }

}
