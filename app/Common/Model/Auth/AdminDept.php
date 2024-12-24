<?php

namespace App\Common\Model\Auth;

use App\Common\Model\BaseModel;

class AdminDept extends BaseModel
{
    protected $table = 'admin_dept';

    /**
     * @notes 删除用户关联部门
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
