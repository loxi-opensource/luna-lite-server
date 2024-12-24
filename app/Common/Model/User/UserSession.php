<?php

namespace App\Common\Model\User;

use App\Common\Model\BaseModel;

/**
 * 用户登录token信息
 */
class UserSession extends BaseModel
{
    protected $table = 'user_session';

    public $timestamps = false;

}
