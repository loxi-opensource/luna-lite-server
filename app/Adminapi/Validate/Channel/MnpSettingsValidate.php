<?php

namespace App\Adminapi\Validate\Channel;

use App\Common\Validate\BaseValidate;

/**
 * 小程序设置验证
 */
class MnpSettingsValidate extends BaseValidate
{
    protected $messages = [
        'app_id.required' => '请填写AppID',
        'app_secret.required' => '请填写AppSecret',
    ];

    public function rules($scene = '')
    {
        $rules = [
            'default' => [
                'app_id' => 'required',
                'app_secret' => 'required',
            ],
        ];

        return $rules[$scene] ?? $rules['default'];
    }
}
