<?php

namespace App\Common\Model\Tools;

use App\Common\Model\BaseModel;

/**
 * 代码生成器-数据表字段信息模型
 */
class GenerateColumn extends BaseModel
{
    protected $table = 'generate_column';

    /**
     * @notes 关联table表
     */
    public function generateTable()
    {
        return $this->belongsTo(GenerateTable::class, 'id', 'table_id');
    }

}
