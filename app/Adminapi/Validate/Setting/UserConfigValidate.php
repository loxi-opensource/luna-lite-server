<?php

namespace App\Adminapi\Validate\Setting;

use App\Common\Validate\BaseValidate;

class UserConfigValidate extends BaseValidate
{
    protected $messages = [
        'default_avatar.required' => '请上传用户默认头像',
        'login_way.required' => '请选择登录方式',
        'login_way.array' => '登录方式值错误',
        'coerce_mobile.required' => '请选择注册强制绑定手机',
        'coerce_mobile.in' => '注册强制绑定手机值错误',
        'wechat_auth.in' => '公众号微信授权登录值错误',
        'third_auth.in' => '第三方登录值错误',
        'login_agreement.in' => '政策协议值错误',
    ];

    public function rules($scene = '')
    {
        $rules = [
            'login_way' => 'required|array',
            'coerce_mobile' => 'required|in:0,1',
            'login_agreement' => 'in:0,1',
            'third_auth' => 'in:0,1',
            'wechat_auth' => 'in:0,1',
            'default_avatar' => 'required',
        ];

        $scenes = [
            'user' => ['default_avatar'],
            'register' => ['login_way', 'coerce_mobile', 'login_agreement', 'third_auth', 'wechat_auth'],
        ];

        if (isset($scenes[$scene])) {
            return array_intersect_key($rules, array_flip($scenes[$scene]));
        }

        return $rules;
    }
}
