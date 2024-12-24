<?php

namespace App\Adminapi\Validate\Auth;

use App\Common\Model\Auth\SystemMenu;
use App\Common\Model\Auth\SystemRole;
use App\Common\Validate\BaseValidate;
use Closure;

class MenuValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'add' => [
                'pid' => [
                    'required',
                    function ($attribute, $value, Closure $fail) {
                        if (!empty(request()->input('id')) && request()->input('id') == $value) {
                            return $fail('上级菜单不能选择自己');
                        }
                    },
                ],
                'type' => 'required|in:M,C,A',
                'name' => [
                    'required',
                    'string',
                    'between:1,30',
                    function ($attribute, $value, Closure $fail) {
                        if (request()->input('type') != 'M') {
                            return;
                        }

                        $where = [
                            ['type', '=', request()->input('type')],
                            ['name', '=', $value],
                        ];

                        if (!empty(request()->input('id'))) {
                            $where[] = ['id', '<>', request()->input('id')];
                        }

                        $check = SystemMenu::where($where)->first();

                        if ($check) {
                            return $fail('菜单名称已存在');
                        }
                    },
                ],
                'icon' => 'nullable|max:100',
                'sort' => 'required|integer|min:0',
                'perms' => 'nullable|max:100',
                'paths' => 'nullable|max:200',
                'component' => 'nullable|max:200',
                'selected' => 'nullable|max:200',
                'params' => 'nullable|max:200',
                'is_cache' => 'required|in:0,1',
                'is_show' => 'required|in:0,1',
                'is_disable' => 'required|in:0,1',
            ],
            'detail' => [
                'id' => 'required',
            ],
            'delete' => [
                'id' => [
                    'required',
                    function ($attribute, $value, Closure $fail) {
                        $hasChild = SystemMenu::query()->where('pid', $value)->exists();
                        if ($hasChild) {
                            return $fail('存在子菜单,不允许删除');
                        }

                        $isBindRole = SystemRole::query()->whereHas('roleMenuIndex', function ($query) use ($value) {
                            $query->where('menu_id', $value);
                        })->exists();

                        if ($isBindRole) {
                            return $fail('已分配菜单不可删除');
                        }
                    },
                ],
            ],
            'status' => [
                'id' => 'required',
                'is_disable' => 'required|in:0,1',
            ],
        ];

        $rules['edit'] = array_merge($rules['add'], [
            'id' => 'required',
        ]);
        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'id.required' => '参数缺失',
        'pid.required' => '请选择上级菜单',
        'type.required' => '请选择菜单类型',
        'type.in' => '菜单类型参数值错误',
        'name.required' => '请填写菜单名称',
        'name.between' => '菜单名称长度需为1~30个字符',
        'icon.max' => '图标名称不能超过100个字符',
        'sort.required' => '请填写排序',
        'sort.min' => '排序值需大于或等于0',
        'perms.max' => '权限字符不能超过100个字符',
        'paths.max' => '路由地址不能超过200个字符',
        'component.max' => '组件路径不能超过200个字符',
        'selected.max' => '选中菜单路径不能超过200个字符',
        'params.max' => '路由参数不能超过200个字符',
        'is_cache.required' => '请选择缓存状态',
        'is_cache.in' => '缓存状态参数值错误',
        'is_show.required' => '请选择显示状态',
        'is_show.in' => '显示状态参数值错误',
        'is_disable.required' => '请选择菜单状态',
        'is_disable.in' => '菜单状态参数值错误',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
