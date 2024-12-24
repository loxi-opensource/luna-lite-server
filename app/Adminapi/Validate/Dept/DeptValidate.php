<?php

namespace App\Adminapi\Validate\Dept;

use App\Common\Model\Auth\AdminDept;
use App\Common\Model\Dept\Dept;
use App\Common\Validate\BaseValidate;
use Closure;
use Illuminate\Validation\Rule;

class DeptValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'add' => [
                'pid' => [
                    'required',
                    'integer',
                    function ($attribute, $value, Closure $fail) {
                        $dept = Dept::find($value);
                        if (!$dept) {
                            return $fail('部门不存在');
                        }
                    },
                ],
                'name' => ['required', 'string', 'between:1,30', Rule::unique('dept', 'name')->whereNull('delete_time')],
                'status' => 'required|in:0,1',
                'sort' => 'integer|min:0',
            ],
            'detail' => [
                'id' => 'required|exists:dept,id',
            ],
            'edit' => [
                'id' => 'required|exists:dept,id',
                'pid' => [
                    'required',
                    'integer',
                    function ($attribute, $value, Closure $fail) {
                        $dept = Dept::find(request()->id);
                        if (!$dept) {
                            return $fail('当前部门信息缺失');
                        }

                        if ($dept->pid == 0) {
                            return;
                        }

                        if (request()->id == $value) {
                            return $fail('上级部门不可是当前部门');
                        }

                        $leaderDept = Dept::find($value);
                        if (!$leaderDept) {
                            return $fail('部门不存在');
                        }
                    },
                ],
                'name' => ['required', 'string', 'between:1,30', Rule::unique('dept', 'name')->whereNull('delete_time')->ignore(request()->id)],
                'status' => 'required|in:0,1',
                'sort' => 'integer|min:0',
            ],
            'delete' => [
                'id' => [
                    'required',
                    'exists:dept,id',
                    function ($attribute, $value, Closure $fail) {
                        $hasLower = Dept::where(['pid' => $value])->first();
                        if ($hasLower) {
                            return $fail('已关联下级部门,暂不可删除');
                        }

                        $check = AdminDept::where(['dept_id' => $value])->first();
                        if ($check) {
                            return $fail('已关联管理员，暂不可删除');
                        }

                        $dept = Dept::find($value);
                        if ($dept && $dept->pid == 0) {
                            return $fail('顶级部门不可删除');
                        }
                    },
                ],
            ],
        ];

        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'id.required' => '参数缺失',
        'name.required' => '请填写部门名称',
        'name.between' => '部门名称长度须在1-30位字符',
        'name.unique' => '部门名称已存在',
        'sort.min' => '排序值不正确',
        'pid.required' => '请选择上级部门',
        'pid.integer' => '上级部门参数错误',
        'status.required' => '请选择部门状态',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
