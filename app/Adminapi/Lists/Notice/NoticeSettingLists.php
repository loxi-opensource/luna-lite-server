<?php

namespace App\Adminapi\Lists\Notice;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Lists\ListsSearchInterface;
use App\Common\Model\Notice\NoticeSetting;

/**
 * 通知设置
 */
class NoticeSettingLists extends BaseAdminDataLists implements ListsSearchInterface
{
    /**
     * @notes 搜索条件
     * @return \string[][]
     */
    public function setSearch(): array
    {
        return [
            '=' => ['recipient', 'type']
        ];
    }

    /**
     * @notes 通知设置列表
     * @return array
     */
    public function lists(): array
    {
        $lists = (new NoticeSetting())
            ->applySearchWhere($this->searchWhere)
            ->get()
            ->toArray();

        return $lists;
    }

    /**
     * @notes 通知设置数量
     * @return int
     */
    public function count(): int
    {
        return (new NoticeSetting())->applySearchWhere($this->searchWhere)->count();
    }
}
