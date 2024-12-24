<?php

namespace App\Adminapi\Validate;

use App\Common\Validate\BaseValidate;

/**
 * 文件验证
 */
class FileValidate extends BaseValidate
{

    protected $messages = [
        'id.required' => '缺少id参数',
        'cid.required' => '缺少cid参数',
        'ids.required' => '缺少ids参数',
        'type.required' => '缺少type参数',
        'pid.required' => '缺少pid参数',
        'name.required' => '请填写分组名称',
        'name.max' => '分组名称长度须为20字符内',
        'ids.array' => 'ids参数必须为数组',
        'id.number' => 'id参数必须为数字',
        'cid.number' => 'cid参数必须为数字',
        'type.in' => 'type参数值不正确',
        'pid.number' => 'pid参数必须为数字',
    ];

    public function rules($scene = '')
    {
        $rules = [
            'id' => [
                'required',
                'numeric',
            ],
            'cid' => [
                'required',
                'numeric',
            ],
            'ids' => [
                'required',
                'array',
            ],
            'type' => [
                'required',
                'in:10,20,30',
            ],
            'pid' => [
                'required',
                'numeric',
            ],
            'name' => [
                'required',
                'max:20',
            ],
        ];

        $scenes = [
            'id' => ['id'],
            'rename' => ['id', 'name'],
            'addCate' => ['type', 'pid', 'name'],
            'editCate' => ['id', 'name'],
            'move' => ['ids', 'cid'],
            'delete' => ['ids'],
        ];

        if (isset($scenes[$scene])) {
            return array_intersect_key($rules, array_flip($scenes[$scene]));
        }

        return $rules; // 默认返回所有规则
    }
}
