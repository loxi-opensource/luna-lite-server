<?php

namespace App\Adminapi\Validate\Notice;

use App\Common\Validate\BaseValidate;

class SmsConfigValidate extends BaseValidate
{
    protected $messages = [
        'type.required' => '请选择类型',
        'sign.required' => '请输入签名',
        'app_id.required_if' => '请输入app_id',
        'app_key.required_if' => '请输入app_key',
        'secret_id.required_if' => '请输入secret_id',
        'secret_key.required' => '请输入secret_key',
        'status.required' => '请选择状态',
    ];

    public function rules($scene = '')
    {
        $rules = [
            'setConfig' => [
                'type' => 'required',
                'sign' => 'required',
                'app_id' => 'required_if:type,tencent',
                'app_key' => 'required_if:type,ali',
                'secret_id' => 'required_if:type,tencent',
                'secret_key' => 'required',
                'status' => 'required',
            ],
            'detail' => [
                'type' => 'required',
            ],
        ];

        return $rules[$scene] ?? [];
    }
}
