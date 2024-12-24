<?php

namespace App\Common\Model\User;

use App\Common\Model\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 账户流水记录模型
 */
class UserAccountLog extends BaseModel
{
    protected $table = 'user_account_log';

    use SoftDeletes;

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

}
