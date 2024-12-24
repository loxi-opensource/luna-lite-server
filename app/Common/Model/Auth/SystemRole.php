<?php

namespace App\Common\Model\Auth;

use App\Common\Model\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemRole extends BaseModel
{
    use SoftDeletes;

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

    protected $table = 'system_role';

    /**
     * @notes 角色与菜单关联关系
     */
    public function roleMenuIndex()
    {
        return $this->hasMany(SystemRoleMenu::class, 'role_id');
    }
}
