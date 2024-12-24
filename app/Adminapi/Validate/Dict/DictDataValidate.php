<?php

namespace App\Adminapi\Validate\Dict;

use App\Common\Model\Dict\DictType;
use App\Common\Validate\BaseValidate;
use Closure;

class DictDataValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'add' => [
                'name' => 'required|string|between:1,255',
                'value' => 'required',
                'type_id' => [
                    'required',
                    function ($attribute, $value, Closure $fail) {
                        $type = DictType::find($value);
                        if (!$type) {
                            return $fail('字典类型不存在');
                        }
                    },
                ],
                'status' => 'required|in:0,1',
            ],
            'id' => [
                'id' => 'required|exists:dict_data,id',
            ],
            'edit' => [
                'id' => 'required|exists:dict_data,id',
                'name' => 'required|string|between:1,255',
                'value' => 'required',
                'status' => 'required|in:0,1',
            ],
        ];

        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'id.required' => '参数缺失',
        'name.required' => '请填写字典数据名称',
        'name.between' => '字典数据名称长度须在1-255位字符',
        'value.required' => '请填写字典数据值',
        'type_id.required' => '字典类型缺失',
        'status.required' => '请选择字典数据状态',
        'status.in' => '字典数据状态参数错误',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
