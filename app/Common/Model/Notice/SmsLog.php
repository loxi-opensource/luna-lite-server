<?php

namespace App\Common\Model\Notice;

use App\Common\Model\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 短信记录模型
 */
class SmsLog extends BaseModel
{
    protected $table = 'sms_log';

    use SoftDeletes;

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

}
