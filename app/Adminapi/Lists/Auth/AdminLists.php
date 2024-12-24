<?php

namespace App\Adminapi\Lists\Auth;

use App\Adminapi\Lists\BaseAdminDataLists;
use App\Common\Lists\ListsExcelInterface;
use App\Common\Lists\ListsExtendInterface;
use App\Common\Lists\ListsSearchInterface;
use App\Common\Lists\ListsSortInterface;
use App\Common\Model\Auth\Admin;
use App\Common\Model\Auth\AdminRole;
use App\Common\Model\Auth\SystemRole;
use App\Common\Model\Dept\Dept;
use App\Common\Model\Dept\Jobs;

/**
 * 管理员列表
 */
class AdminLists extends BaseAdminDataLists implements ListsExtendInterface, ListsSearchInterface, ListsSortInterface, ListsExcelInterface
{
    /**
     * @notes 设置导出字段
     * @return string[]
     */
    public function setExcelFields(): array
    {
        return [
            'account' => '账号',
            'name' => '名称',
            'role_name' => '角色',
            'dept_name' => '部门',
            'create_time' => '创建时间',
            'login_time' => '最近登录时间',
            'login_ip' => '最近登录IP',
            'disable_desc' => '状态',
        ];
    }


    /**
     * @notes 设置导出文件名
     * @return string
     */
    public function setFileName(): string
    {
        return '管理员列表';
    }


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     */
    public function setSearch(): array
    {
        return [
            '%like%' => ['name', 'account'],
        ];
    }


    /**
     * @notes 设置支持排序字段
     * @return string[]
     * @remark 格式: ['前端传过来的字段名' => '数据库中的字段名'];
     */
    public function setSortFields(): array
    {
        return ['create_time' => 'create_time', 'id' => 'id'];
    }


    /**
     * @notes 设置默认排序
     * @return string[]
     */
    public function setDefaultOrder(): array
    {
        return ['id' => 'desc'];
    }

    /**
     * @notes 查询条件
     * @return array
     */
    public function queryWhere()
    {
//        return [
//            ['id', 'in', [2,3]]
//        ];
        $where = [];
        if (isset($this->params['role_id']) && $this->params['role_id'] != '') {
            $adminIds = AdminRole::query()->where('role_id', $this->params['role_id'])->pluck('admin_id');
            if (!empty($adminIds)) {
                $where[] = ['id', 'in', $adminIds];
            }
        }
        return $where;
    }


    /**
     * @notes 获取管理列表
     * @return array
     */
    public function lists(): array
    {
        $field = [
            'id', 'name', 'account', 'create_time', 'disable', 'root',
            'login_time', 'login_ip', 'multipoint_login', 'avatar'
        ];

        $adminLists = Admin::query()->select($field)
            ->offset($this->limitOffset)
            ->limit($this->limitLength)
            ->applySortOrder($this->sortOrder)
            ->applySearchWhere($this->searchWhere)
            ->applySearchWhere($this->queryWhere())
            ->get()->toArray();

        // 角色数组（'角色id'=>'角色名称')
        $roleLists = SystemRole::query()->pluck('name', 'id');
        // 部门列表
        $deptLists = Dept::query()->pluck('name', 'id');
        // 岗位列表
        $jobsLists = Jobs::query()->pluck('name', 'id');

        //管理员列表增加角色名称
        foreach ($adminLists as $k => $v) {
            $roleName = '';
            if ($v['root'] == 1) {
                $roleName = '系统管理员';
            } else {
                foreach ($v['role_id'] as $roleId) {
                    $roleName .= $roleLists[$roleId] ?? '';
                    $roleName .= '/';
                }
            }

            $deptName = '';
            foreach ($v['dept_id'] as $deptId) {
                $deptName .= $deptLists[$deptId] ?? '';
                $deptName .= '/';
            }

            $jobsName = '';
            foreach ($v['jobs_id'] as $jobsId) {
                $jobsName .= $jobsLists[$jobsId] ?? '';
                $jobsName .= '/';
            }

            $adminLists[$k]['role_name'] = trim($roleName, '/');
            $adminLists[$k]['dept_name'] = trim($deptName, '/');
            $adminLists[$k]['jobs_name'] = trim($jobsName, '/');
        }

        return $adminLists;
    }

    /**
     * @notes 获取数量
     * @return int
     */
    public function count(): int
    {
        return Admin::query()
            ->applySearchWhere($this->searchWhere)
            ->applySearchWhere($this->queryWhere())
            ->count();
    }

    public function extend()
    {
        return [];
    }
}
