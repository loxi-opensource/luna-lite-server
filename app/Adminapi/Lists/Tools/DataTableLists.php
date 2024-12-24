<?php

namespace App\Adminapi\Lists\Tools;

use App\Adminapi\Lists\BaseAdminDataLists;
use Illuminate\Support\Facades\DB;

/**
 * 数据表列表
 */
class DataTableLists extends BaseAdminDataLists
{
    /**
     * @notes 查询结果
     */
    public function queryResult()
    {
        $sql = 'SHOW TABLE STATUS WHERE 1=1 ';
        if (!empty($this->params['name'])) {
            $sql .= "AND name LIKE '%" . $this->params['name'] . "%'";
        }
        if (!empty($this->params['comment'])) {
            $sql .= "AND comment LIKE '%" . $this->params['comment'] . "%'";
        }
        $results = DB::select($sql);
        $array = json_decode(json_encode($results), true);
        return $array;
    }


    /**
     * @notes 处理列表
     */
    public function lists(): array
    {
        $lists = array_map("array_change_key_case", $this->queryResult());
        $offset = max(0, ($this->pageNo - 1) * $this->pageSize);
        $lists = array_slice($lists, $offset, $this->pageSize, true);
        return array_values($lists);
    }

    /**
     * @notes 获取数量
     */
    public function count(): int
    {
        return count($this->queryResult());
    }

}
