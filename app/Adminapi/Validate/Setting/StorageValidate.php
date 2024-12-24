<?php

namespace App\Adminapi\Validate\Setting;

use App\Common\Validate\BaseValidate;

/**
 * 存储引擎验证
 */
class StorageValidate extends BaseValidate
{
    protected $messages = [
        'engine.required' => '存储引擎参数缺失',
        'status.required' => '状态参数缺失',
    ];

    public function rules($scene = '')
    {
        $rules = [
            'engine' => 'required',
            'status' => 'required',
        ];

        $scenes = [
            'setup' => ['engine', 'status'],
            'detail' => ['engine'],
            'change' => ['engine'],
        ];

        if (isset($scenes[$scene])) {
            return array_intersect_key($rules, array_flip($scenes[$scene]));
        }

        return $rules; // 默认返回所有规则
    }
}
