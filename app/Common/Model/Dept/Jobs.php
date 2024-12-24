<?php

namespace App\Common\Model\Dept;

use App\Common\Model\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 岗位模型
 */
class Jobs extends BaseModel
{
    protected $table = 'jobs';

    protected $appends = ['status_desc'];

    use SoftDeletes;

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

    /**
     * @notes 状态描述
     * @param $value
     * @param $data
     * @return string
     */
    public function getStatusDescAttribute()
    {
        return $this->attributes['status'] ? '正常' : '停用';
    }
}
