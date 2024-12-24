<?php

namespace App\Adminapi\Lists\Setting\System;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Lists\ListsExcelInterface;
use App\Common\Lists\ListsSearchInterface;
use App\Common\Model\OperationLog;

/**
 * 日志列表
 */
class LogLists extends BaseAdminDataLists implements ListsSearchInterface, ListsExcelInterface
{
    /**
     * @notes 设置搜索条件
     * @return \string[][]
     */
    public function setSearch(): array
    {
        return [
            '%like%' => ['admin_name', 'url', 'ip', 'type'],
            'between_time' => 'create_time',
        ];
    }

    /**
     * @notes 查看系统日志列表
     * @return array
     */
    public function lists(): array
    {
        $lists = OperationLog::query()
            ->select('id', 'action', 'admin_name', 'admin_id', 'url', 'type', 'params', 'ip', 'create_time')
            ->applySearchWhere($this->searchWhere)
            ->limit($this->limitLength)
            ->offset($this->limitOffset)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

        return $lists;
    }

    /**
     * @notes 查看系统日志总数
     * @return int
     */
    public function count(): int
    {
        return OperationLog::applySearchWhere($this->searchWhere)->count();
    }

    /**
     * @notes 设置导出字段
     * @return string[]
     */
    public function setExcelFields(): array
    {
        return [
            'id' => '记录ID',
            'action' => '操作',
            'admin_name' => '管理员',
            'admin_id' => '管理员ID',
            'url' => '访问链接',
            'type' => '访问方式',
            'params' => '访问参数',
            'ip' => '来源IP',
            'create_time' => '日志时间',
        ];
    }

    /**
     * @notes 设置默认表名
     * @return string
     */
    public function setFileName(): string
    {
        return '系统日志';
    }
}
