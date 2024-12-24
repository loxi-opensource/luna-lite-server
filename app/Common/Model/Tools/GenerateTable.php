<?php

namespace App\Common\Model\Tools;

use App\Common\Enum\GeneratorEnum;
use App\Common\Model\BaseModel;

/**
 * 代码生成器-数据表信息模型
 */
class GenerateTable extends BaseModel
{
    protected $table = 'generate_table';

    public $casts = [
        'menu' => 'array',
        'tree' => 'array',
        'relations' => 'array',
        'delete' => 'array',
    ];

    protected $appends = ['template_type_desc'];

    /**
     * @notes 关联数据表字段
     */
    public function tableColumn()
    {
        return $this->hasMany(GenerateColumn::class, 'table_id', 'id');
    }

    /**
     * @notes 模板类型描述
     */
    public function getTemplateTypeDescAttribute($value)
    {
        return GeneratorEnum::getTemplateTypeDesc($this->attributes['template_type']);
    }

}
