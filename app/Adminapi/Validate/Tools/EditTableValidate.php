<?php

namespace App\Adminapi\Validate\Tools;

use App\Common\Model\Tools\GenerateTable;
use App\Common\Validate\BaseValidate;
use Closure;

class EditTableValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        return [
            'id' => [
                'required',
                function ($attribute, $value, Closure $fail) {
                    $table = GenerateTable::find($value);
                    if (!$table) {
                        return $fail('信息不存在');
                    }
                },
            ],
            'table_name' => 'required',
            'table_comment' => 'required',
            'template_type' => 'required|in:0,1',
            'generate_type' => 'required|in:0,1',
            'module_name' => 'required',
            'table_column' => [
                'required',
                'array',
                function ($attribute, $value, Closure $fail) {
                    foreach ($value as $item) {
                        if (!isset($item['id'])) {
                            return $fail('表字段id参数缺失');
                        }
                        if (!isset($item['query_type'])) {
                            return $fail('请选择查询方式');
                        }
                        if (!isset($item['view_type'])) {
                            return $fail('请选择显示类型');
                        }
                    }
                },
            ],
        ];
    }

    protected $messages = [
        'id.required' => '表id缺失',
        'table_name.required' => '请填写表名称',
        'table_comment.required' => '请填写表描述',
        'template_type.required' => '请选择模板类型',
        'template_type.in' => '模板类型参数错误',
        'generate_type.required' => '请选择生成方式',
        'generate_type.in' => '生成方式类型错误',
        'module_name.required' => '请填写模块名称',
        'table_column.required' => '表字段信息缺失',
        'table_column.array' => '表字段信息类型错误',
    ];

    public function messages()
    {
        return $this->messages;
    }
}

