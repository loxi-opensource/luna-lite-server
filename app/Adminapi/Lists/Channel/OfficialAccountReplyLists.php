<?php

namespace App\Adminapi\Lists\Channel;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Lists\ListsSearchInterface;
use App\Common\Model\Channel\OfficialAccountReply;

/**
 * 微信公众号回复列表
 */
class OfficialAccountReplyLists extends BaseAdminDataLists implements ListsSearchInterface
{

    /**
     * @notes 设置搜索
     */
    public function setSearch(): array
    {
        return [
            '=' => ['reply_type']
        ];
    }

    /**
     * @notes 回复列表
     */
    public function lists(): array
    {
        $lists = OfficialAccountReply::select([
            'id', 'name', 'keyword', 'matching_type',
            'content_type', 'content', 'status', 'sort',
            'matching_type as matching_type_desc',
            'content_type as content_type_desc',
            'status as status_desc'
        ])
            ->where($this->searchWhere)
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->limit($this->limitLength)
            ->offset($this->limitOffset)
            ->get()
            ->toArray();

        return $lists;
    }

    /**
     * @notes 回复记录数
     */
    public function count(): int
    {
        $count = OfficialAccountReply::where($this->searchWhere)->count();

        return $count;
    }
}
