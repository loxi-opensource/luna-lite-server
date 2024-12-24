<?php

namespace App\Adminapi\Validate\Dict;

use App\Common\Model\Dict\DictData;
use App\Common\Validate\BaseValidate;
use Closure;
use Illuminate\Validation\Rule;

class DictTypeValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'add' => [
                'name' => 'required|string|between:1,255',
                'type' => 'required|unique:dict_type,type',
                'status' => 'required|in:0,1',
                'remark' => 'nullable|string|max:200',
            ],
            'detail' => [
                'id' => 'required|exists:dict_type,id',
            ],
            'edit' => [
                'id' => 'required|exists:dict_type,id',
                'name' => 'required|string|between:1,255',
                'type' => ['required', Rule::unique('dict_type', 'type')->ignore(request()->id)],
                'status' => 'required|in:0,1',
                'remark' => 'nullable|string|max:200',
            ],
            'delete' => [
                'id' => [
                    'required',
                    'exists:dict_type,id',
                    function ($attribute, $value, Closure $fail) {
                        $dictData = DictData::where('type_id', $value)->first();
                        if ($dictData) {
                            return $fail('字典类型已被使用，请先删除绑定该字典类型的数据');
                        }
                    },
                ],
            ],
        ];

        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'id.required' => '参数缺失',
        'name.required' => '请填写字典名称',
        'name.between' => '字典名称长度须在1~255位字符',
        'type.required' => '请填写字典类型',
        'type.unique' => '字典类型已存在',
        'status.required' => '请选择状态',
        'remark.max' => '备注长度不能超过200',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
