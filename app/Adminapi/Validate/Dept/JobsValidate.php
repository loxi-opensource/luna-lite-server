<?php

namespace App\Adminapi\Validate\Dept;

use App\Common\Model\Auth\AdminJobs;
use App\Common\Validate\BaseValidate;
use Closure;
use Illuminate\Validation\Rule;

class JobsValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'add' => [
                'name' => ['required', 'string', 'between:1,50', Rule::unique('jobs', 'name')->whereNull('delete_time')],
                'code' => ['required', Rule::unique('jobs', 'code')->whereNull('delete_time')],
                'status' => 'required|in:0,1',
                'sort' => 'integer|min:0',
            ],
            'detail' => [
                'id' => 'required|exists:jobs,id',
            ],
            'edit' => [
                'id' => 'required|exists:jobs,id',
                'name' => ['required', 'string', 'between:1,50', Rule::unique('jobs', 'name')->whereNull('delete_time')->ignore(request()->id)],
                'code' => ['required', Rule::unique('jobs', 'code')->whereNull('delete_time')->ignore(request()->id)],
                'status' => 'required|in:0,1',
                'sort' => 'integer|min:0',
            ],
            'delete' => [
                'id' => [
                    'required',
                    'exists:jobs,id',
                    function ($attribute, $value, Closure $fail) {
                        $check = AdminJobs::where(['jobs_id' => $value])->first();
                        if ($check) {
                            return $fail('已关联管理员，暂不可删除');
                        }
                    },
                ],
            ],
        ];

        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'id.required' => '参数缺失',
        'name.required' => '请填写岗位名称',
        'name.between' => '岗位名称长度须在1-50位字符',
        'name.unique' => '岗位名称已存在',
        'code.required' => '请填写岗位编码',
        'code.unique' => '岗位编码已存在',
        'sort.min' => '排序值不正确',
        'status.required' => '请选择岗位状态',
        'status.in' => '岗位状态值错误',
    ];


    public function messages()
    {
        return $this->messages;
    }
}
