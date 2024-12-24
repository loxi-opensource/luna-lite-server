<?php

namespace App\Common\Model\Auth;

use App\Common\Model\BaseModel;

class AdminJobs extends BaseModel
{
    protected $table = 'admin_jobs';

    /**
     * @notes 删除用户关联岗位
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
