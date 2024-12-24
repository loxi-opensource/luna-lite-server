<?php

namespace App\Common\Model\Dict;

use App\Common\Model\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 字典类型模型
 */
class DictType extends BaseModel
{
    protected $table = 'dict_type';

    protected $appends = ['status_desc'];

    use SoftDeletes;

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

    /**
     * @notes 状态描述
     */
    public function getStatusDescAttribute($value)
    {
        return $this->attributes['status'] ? '正常' : '停用';
    }

}
