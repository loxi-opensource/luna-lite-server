<?php

namespace App\Api\Validate;

use App\Common\Validate\BaseValidate;

/**
 * 微信登录验证
 */
class WechatLoginValidate extends BaseValidate
{
    protected $messages = [
        'code.required' => 'code缺少',
        'nickname.required' => '昵称缺少',
        'headimgurl.required' => '头像缺少',
        'openid.required' => 'openid缺少',
        'access_token.required' => 'access_token缺少',
        'terminal.required' => '终端参数缺少',
        'avatar.required' => '头像缺少',
    ];

    public function rules($scene = '')
    {
        $rules = [
            'default' => [
                'code' => 'required',
                'nickname' => 'required',
                'headimgurl' => 'required',
                'openid' => 'required',
                'access_token' => 'required',
                'terminal' => 'required',
                'avatar' => 'required',
            ],
            'oa' => [
                'code' => 'required',
            ],
            'mnpLogin' => [
                'code' => 'required',
            ],
            'wechatAuth' => [
                'code' => 'required',
            ],
            'updateUser' => [
                'nickname' => 'required',
                'avatar' => 'required',
            ],
        ];

        return $rules[$scene] ?? $rules['default'];
    }

}
