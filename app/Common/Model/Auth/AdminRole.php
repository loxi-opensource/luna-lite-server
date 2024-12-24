<?php

namespace App\Common\Model\Auth;

use App\Common\Model\BaseModel;

class AdminRole extends BaseModel
{
    protected $table = 'admin_role';

    /**
     * @notes 删除用户关联角色
     * @param $adminId
     * @return bool
     * @author 段誉
     * @date 2022/11/25 14:14
     */
    public static function delByUserId($adminId)
    {
        return self::where(['admin_id' => $adminId])->delete();
    }

}
