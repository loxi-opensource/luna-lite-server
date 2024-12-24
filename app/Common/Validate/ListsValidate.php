<?php

namespace App\Common\Validate;

use Closure;
use Illuminate\Support\Facades\Config;

/**
 * 列表参数验证
 */
class ListsValidate extends BaseValidate
{
    public function rules($scene = 'default')
    {
        $rules = [
            'default' => [
                'page_no' => 'integer|gt:0',
                'page_size' => [
                    'integer', 'gt:0',
                    function (string $attribute, mixed $value, Closure $fail) {
                        $result = $this->pageSizeMax($value);
                        if ($result !== true) {
                            return $fail($result);
                        }
                    },
                ],
                'page_start' => 'integer|gt:0',
                'page_end' => 'integer|gt:0|gte:page_start', // 使用 gte 替代 egt
                'page_type' => 'in:0,1',
                'order_by' => 'in:desc,asc',
                'start_time' => 'date',
                'end_time' => 'date|after:start_time', // 使用 after 替代 gt
                'start' => 'numeric', // 使用 numeric 替代 number
                'end' => 'numeric',
                'export' => 'in:1,2',
            ]
        ];
        return $rules[$scene] ?? [];
    }

    public function messages()
    {
        return [
            'page_end.gte' => '导出范围设置不正确，请重新选择',
            'end_time.after' => '搜索的时间范围不正确',
        ];
    }

    /**
     * @notes 查询数据量判断
     * @param $value
     * @param $rule
     * @param $data
     * @return bool
     */
    public function pageSizeMax($value)
    {
        $pageSizeMax = Config::get('project.lists.page_size_max');
        if ($pageSizeMax < $value) {
            return '已超出系统限制数量，请分页查询或导出，' . '当前最多记录数为：' . $pageSizeMax;
        }
        return true;
    }


}
