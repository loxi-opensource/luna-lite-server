<?php

namespace App\Adminapi\Lists\File;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Adminapi\Logic\FileLogic;
use App\Common\Lists\ListsSearchInterface;
use App\Common\Model\File\File;
use App\Common\Service\FileService;

/**
 * 文件列表
 */
class FileLists extends BaseAdminDataLists implements ListsSearchInterface
{

    /**
     * @notes 文件搜索条件
     */
    public function setSearch(): array
    {
        return [
            '=' => ['type', 'source'],
            '%like%' => ['name']
        ];
    }

    /**
     * @notes 额外查询处理
     */
    public function queryWhere(): array
    {
        $where = [];

        if (!empty($this->params['cid'])) {
            $cateChild = FileLogic::getCateIds($this->params['cid']);
            array_push($cateChild, $this->params['cid']);
            $where[] = ['cid', 'in', $cateChild];
        }

        return $where;
    }


    /**
     * @notes 获取文件列表
     */
    public function lists(): array
    {
        $lists = File::query()->select(['id', 'cid', 'type', 'name', 'uri', 'create_time'])
            ->applySearchWhere($this->searchWhere)
            ->applySearchWhere($this->queryWhere())
            ->orderBy('id', 'desc')
            ->limit($this->limitLength)
            ->offset($this->limitOffset)
            ->get()->map(function ($item) {
                $item['url'] = FileService::getFileUrl($item['uri']);
                return $item;
            })->toArray();

        return $lists;
    }


    /**
     * @notes 获取文件数量
     */
    public function count(): int
    {
        return File::query()
            ->applySearchWhere($this->searchWhere)
            ->applySearchWhere($this->queryWhere())
            ->count();
    }
}
