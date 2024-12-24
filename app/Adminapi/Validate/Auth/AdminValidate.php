<?php

namespace App\Adminapi\Validate\Auth;

use App\Common\Model\Auth\Admin;
use App\Common\Validate\BaseValidate;
use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class AdminValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'add' => [
                'account' => 'required|string|between:1,32|unique:admin,account',
                'name' => 'required|string|between:1,16|unique:admin,name',
                'password' => 'required|string|between:6,32',
                'password_confirm' => 'required_with:password|same:password',
                'role_id' => 'required',
                'multipoint_login' => 'required|in:0,1',
                'disable' => 'required|in:0,1',
            ],
            'detail' => [
                'id' => 'required|exists:admin,id',
            ],
            'edit' => [
                'id' => 'required|exists:admin,id',
                'account' => ['required', 'string', 'between:1,32', Rule::unique('admin', 'account')->ignore(request()->id)],
                'name' => ['required', 'string', 'between:1,16', Rule::unique('admin', 'name')->ignore(request()->id)],
                'password' => 'nullable|string|between:6,32',
                'password_confirm' => 'nullable|same:password',
                'role_id' => [
                    function ($attribute, $value, Closure $fail) {
                        $admin = Admin::find(request()->id);
                        if (!$admin) {
                            return $fail('管理员不存在');
                        }

                        if ($admin->root) {
                            return;
                        }

                        if (empty($value)) {
                            return $fail('请选择角色');
                        }
                    },
                ],
                'disable' => [
                    'required',
                    'in:0,1',
                    function ($attribute, $value, Closure $fail) {
                        $admin = Admin::find(request()->id);
                        if (!$admin) {
                            return $fail('管理员不存在');
                        }

                        if ($value && $admin->root) {
                            return $fail('超级管理员不允许被禁用');
                        }
                    },
                ],
                'multipoint_login' => 'required|in:0,1',
            ],
            'delete' => [
                'id' => 'required|exists:admin,id',
            ],
            'editSelf' => [
                'name' => 'required|string|between:1,16',
                'avatar' => 'required',
                'admin_id' => 'required|exists:admin,id',
                'password' => [
                    'nullable',
                    'string',
                    'between:6,32',
                    function ($attribute, $value, Closure $fail) {
                        if (empty(request()->password_old)) {
                            return $fail('请填写当前密码');
                        }

                        $admin = Admin::find(request()->attributes->get('adminId'));
                        if (!$admin) {
                            return $fail('管理员不存在');
                        }

                        $passwordSalt = Config::get('project.unique_identification');
                        $oldPassword = create_password(request()->password_old, $passwordSalt);

                        if ($admin->password !== $oldPassword) {
                            return $fail('当前密码错误');
                        }
                    },
                ],
                'password_confirm' => 'required_with:password|same:password',
            ],
        ];

        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'id.required' => '管理员id不能为空',
        'account.required' => '账号不能为空',
        'account.between' => '账号长度须在1-32位字符',
        'account.unique' => '账号已存在',
        'password.required' => '密码不能为空',
        'password.between' => '密码长度须在6-32位字符',
        'password_confirm.required_with' => '确认密码不能为空',
        'password_confirm.same' => '两次输入的密码不一致',
        'name.required' => '名称不能为空',
        'name.between' => '名称须在1-16位字符',
        'name.unique' => '名称已存在',
        'role_id.required' => '请选择角色',
        'disable.required' => '请选择状态',
        'disable.in' => '状态值错误',
        'multipoint_login.required' => '请选择是否支持多处登录',
        'multipoint_login.in' => '多处登录状态值错误',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
