<?php

namespace App\Common\Model\Notice;

use App\Common\Model\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 通知记录模型
 */
class NoticeRecord extends BaseModel
{
    protected $table = 'notice_record';

    use SoftDeletes;

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

}
