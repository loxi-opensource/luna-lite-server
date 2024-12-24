<?php

namespace App\Adminapi\Logic\Auth;

use App\Common\Cache\AdminAuthCache;
use App\Common\Cache\AdminTokenCache;
use App\Common\Enum\YesNoEnum;
use App\Common\Logic\BaseLogic;
use App\Common\Model\Auth\Admin;
use App\Common\Model\Auth\AdminDept;
use App\Common\Model\Auth\AdminJobs;
use App\Common\Model\Auth\AdminRole;
use App\Common\Model\Auth\AdminSession;
use App\Common\Service\FileService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * 管理员逻辑
 */
class AdminLogic extends BaseLogic
{
    /**
     * @notes 添加管理员
     * @param array $params
     */
    public static function add(array $params)
    {
        DB::beginTransaction();
        try {
            $passwordSalt = Config::get('project.unique_identification');
            $password = create_password($params['password'], $passwordSalt);
            $defaultAvatar = config('project.default_image.admin_avatar');
            $avatar = !empty($params['avatar']) ? FileService::setFileUrl($params['avatar']) : $defaultAvatar;

            $admin = Admin::create([
                'name' => $params['name'],
                'account' => $params['account'],
                'avatar' => $avatar,
                'password' => $password,
                'create_time' => time(),
                'disable' => $params['disable'],
                'multipoint_login' => $params['multipoint_login'],
            ]);

            // 角色、部门、岗位
            self::insertRole($admin->id, $params['role_id'] ?? []);
            self::insertDept($admin->id, $params['dept_id'] ?? []);
            self::insertJobs($admin->id, $params['jobs_id'] ?? []);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 编辑管理员
     * @param array $params
     * @return bool
     */
    public static function edit(array $params): bool
    {
        DB::beginTransaction();
        try {
            // 基础信息
            $data = [
                'name' => $params['name'],
                'account' => $params['account'],
                'disable' => $params['disable'],
                'multipoint_login' => $params['multipoint_login'],
            ];

            // 头像
            $data['avatar'] = !empty($params['avatar']) ? FileService::setFileUrl($params['avatar']) : '';

            // 密码
            if (!empty($params['password'])) {
                $passwordSalt = Config::get('project.unique_identification');
                $data['password'] = create_password($params['password'], $passwordSalt);
            }

            // 检查角色变化
            $roleIds = AdminRole::where('admin_id', $params['id'])->pluck('role_id')->toArray();
            $editRole = !empty(array_diff($roleIds, $params['role_id']));

            // 禁用或更换角色后.设置token过期
            if ($params['disable'] == 1 || $editRole) {
                $tokenArr = AdminSession::where('admin_id', $params['id'])->get();
                foreach ($tokenArr as $token) {
                    self::expireToken($token->token);
                }
            }

            Admin::where('id', $params['id'])->update($data);
            (new AdminAuthCache($params['id']))->clearAuthCache();

            // 删除旧的关联信息
            AdminRole::delByUserId($params['id']);
            AdminDept::delByUserId($params['id']);
            AdminJobs::delByUserId($params['id']);

            // 新增角色、部门、岗位
            self::insertRole($params['id'], $params['role_id']);
            self::insertDept($params['id'], $params['dept_id'] ?? []);
            self::insertJobs($params['id'], $params['jobs_id'] ?? []);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 删除管理员
     * @param array $params
     * @return bool
     */
    public static function delete(array $params): bool
    {
        DB::beginTransaction();
        try {
            $admin = Admin::findOrFail($params['id']);
            if ($admin->root == YesNoEnum::YES) {
                throw new \Exception("超级管理员不允许被删除");
            }
            Admin::destroy($params['id']);

            // 设置token过期
            $tokenArr = AdminSession::where('admin_id', $params['id'])->get();
            foreach ($tokenArr as $token) {
                self::expireToken($token->token);
            }
            (new AdminAuthCache($params['id']))->clearAuthCache();

            // 删除旧的关联信息
            AdminRole::delByUserId($params['id']);
            AdminDept::delByUserId($params['id']);
            AdminJobs::delByUserId($params['id']);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 过期token
     * @param $token
     * @return bool
     */
    public static function expireToken($token): bool
    {
        $adminSession = AdminSession::where('token', $token)->with('admin')->first();

        if (!$adminSession) {
            return false;
        }

        $time = time();
        $adminSession->expire_time = $time;
        $adminSession->update_time = $time;
        $adminSession->save();

        return (new AdminTokenCache())->deleteAdminInfo($token);
    }

    /**
     * @notes 查看管理员详情
     * @param $params
     * @return array
     */
    public static function detail($params, $action = 'detail'): array
    {
        $admin = Admin::select([
            'id', 'account', 'name', 'disable', 'root',
            'multipoint_login', 'avatar'
        ])->findOrFail($params['id'])->toArray();

        if ($action == 'detail') {
            return $admin;
        }

        $result['user'] = $admin;
        // 当前管理员角色拥有的菜单
        $result['menu'] = MenuLogic::getMenuByAdminId($params['id']);
        // 当前管理员角色拥有的按钮权限
        $result['permissions'] = AuthLogic::getBtnAuthByRoleId($admin);
        return $result;
    }

    /**
     * @notes 编辑超级管理员
     * @param $params
     * @return Admin
     */
    public static function editSelf($params)
    {
        $data = [
            'id' => $params['admin_id'],
            'name' => $params['name'],
            'avatar' => FileService::setFileUrl($params['avatar']),
        ];

        if (!empty($params['password'])) {
            $passwordSalt = Config::get('project.unique_identification');
            $data['password'] = create_password($params['password'], $passwordSalt);
        }

        return Admin::where('id', $params['admin_id'])->update($data);
    }

    /**
     * @notes 新增角色
     * @param $adminId
     * @param $roleIds
     */
    public static function insertRole($adminId, $roleIds)
    {
        if (!empty($roleIds)) {
            $roleData = [];
            foreach ($roleIds as $roleId) {
                $roleData[] = [
                    'admin_id' => $adminId,
                    'role_id' => $roleId,
                ];
            }
            AdminRole::insert($roleData);
        }
    }

    /**
     * @notes 新增部门
     * @param $adminId
     * @param $deptIds
     */
    public static function insertDept($adminId, $deptIds)
    {
        if (!empty($deptIds)) {
            $deptData = [];
            foreach ($deptIds as $deptId) {
                $deptData[] = [
                    'admin_id' => $adminId,
                    'dept_id' => $deptId
                ];
            }
            AdminDept::insert($deptData);
        }
    }

    /**
     * @notes 新增岗位
     * @param $adminId
     * @param $jobsIds
     */
    public static function insertJobs($adminId, $jobsIds)
    {
        if (!empty($jobsIds)) {
            $jobsData = [];
            foreach ($jobsIds as $jobsId) {
                $jobsData[] = [
                    'admin_id' => $adminId,
                    'jobs_id' => $jobsId
                ];
            }
            AdminJobs::insert($jobsData);
        }
    }
}
