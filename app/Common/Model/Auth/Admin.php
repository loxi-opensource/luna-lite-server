<?php

namespace App\Common\Model\Auth;

use App\Common\Enum\YesNoEnum;
use App\Common\Model\BaseModel;
use App\Common\Service\FileService;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends BaseModel
{
    protected $table = 'admin';

    use SoftDeletes;

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

    protected $appends = [
        'role_id',
        'dept_id',
        'jobs_id',
        'disable_desc',
    ];

    /**
     * @notes 关联角色id
     * @return array
     */
    public function getRoleIdAttribute()
    {
        return $this->roles()->pluck('role_id')->toArray();
    }

    /**
     * @notes 关联部门id
     * @return array
     */
    public function getDeptIdAttribute()
    {
        return $this->departments()->pluck('dept_id')->toArray();
    }

    /**
     * @notes 关联岗位id
     * @return array
     */
    public function getJobsIdAttribute()
    {
        return $this->jobs()->pluck('jobs_id')->toArray();
    }

    /**
     * @notes 获取禁用状态描述
     * @return string
     */
    public function getDisableDescAttribute()
    {
        return YesNoEnum::getDisableDesc($this->attributes['disable']);
    }

    /**
     * @notes 最后登录时间获取器 - 格式化：年-月-日 时:分:秒
     * @return string
     */
    public function getLoginTimeAttribute()
    {
        return empty($this->attributes['login_time']) ? '' : date('Y-m-d H:i:s', $this->attributes['login_time']);
    }

    /**
     * @notes 头像获取器 - 头像路径添加域名
     * @return string
     */
    public function getAvatarAttribute()
    {
        return empty($this->attributes['avatar'])
            ? FileService::getFileUrl(config('project.default_image.admin_avatar'))
            : FileService::getFileUrl(trim($this->attributes['avatar'], '/'));
    }

    /**
     * 角色关系
     */
    public function roles()
    {
        return $this->hasMany(AdminRole::class, 'admin_id');
    }

    /**
     * 部门关系
     */
    public function departments()
    {
        return $this->hasMany(AdminDept::class, 'admin_id');
    }

    /**
     * 岗位关系
     */
    public function jobs()
    {
        return $this->hasMany(AdminJobs::class, 'admin_id');
    }


}
