<?php

namespace App\Adminapi\Validate\Auth;

use App\Common\Model\Auth\AdminRole;
use App\Common\Validate\BaseValidate;
use Closure;

class RoleValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'add' => [
                'name' => 'required|max:64|unique:system_role,name',
                'menu_id' => 'array',
            ],
            'edit' => [
                'name' => 'required|max:64|unique:system_role,name,' . request()->id,
                'menu_id' => 'array',
                'sort' => 'integer',
                'desc' => 'nullable|string',
                'id' => [
                    'required',
                    'exists:system_role,id',
                ],
            ],
            'detail' => [
                'id' => [
                    'required',
                    'exists:system_role,id',
                ],
            ],
            'delete' => [
                'id' => [
                    'required',
                    'exists:system_role,id',
                    function (string $attribute, mixed $value, Closure $fail) {
                        if (AdminRole::where('role_id', $value)->exists()) {
                            return $fail('有管理员在使用该角色，不允许删除');
                        }
                    },
                ],
            ],
        ];
        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'id.required' => '请选择角色',
        'id.exists' => '角色不存在',
        'name.required' => '请输入角色名称',
        'name.max' => '角色名称最长为64个字符',
        'name.unique' => '角色名称已存在',
        'menu_id.array' => '权限格式错误',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
