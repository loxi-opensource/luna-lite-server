<?php

namespace App\Adminapi\Validate\Tools;

use App\Common\Model\Tools\GenerateTable;
use App\Common\Validate\BaseValidate;
use Closure;
use Illuminate\Support\Facades\DB;

class GenerateTableValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'select' => [
                'table' => [
                    'required',
                    'array',
                    function ($attribute, $value, Closure $fail) {
                        foreach ($value as $item) {
                            if (!isset($item['name']) || !isset($item['comment'])) {
                                return $fail('参数缺失');
                            }
                            $exist = DB::select("SHOW TABLES LIKE '" . $item['name'] . "'");
                            if (empty($exist)) {
                                return $fail('当前数据库不存在' . $item['name'] . '表');
                            }
                        }
                    },
                ],
            ],
            'id' => [
                'id' => [
                    'required',
                    function ($attribute, $value, Closure $fail) {
                        if (!is_array($value)) {
                            $value = [$value];
                        }

                        foreach ($value as $item) {
                            $table = GenerateTable::find($item);
                            if (!$table) {
                                return $fail('信息不存在');
                            }
                        }
                    },
                ],
            ],
            'download' => [
                'file' => 'required',
            ],
        ];

        return $rules[$scene] ?? [];
    }


    protected $messages = [
        'id.required' => '参数缺失',
        'table.required' => '参数缺失',
        'table.array' => '参数类型错误',
        'file.required' => '下载失败',
    ];

    public function messages()
    {
        return $this->messages;
    }
}

