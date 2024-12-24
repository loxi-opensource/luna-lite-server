<?php

namespace App\Adminapi\Validate\Setting;

use App\Common\Validate\BaseValidate;

class WebSettingValidate extends BaseValidate
{
    protected $messages = [
        'name.required' => '请填写网站名称',
        'name.max' => '网站名称最长为30个字符',
        'web_favicon.required' => '请上传网站图标',
        'web_logo.required' => '请上传网站logo',
        'login_image.required' => '请上传登录页广告图',
        'shop_name.required' => '请填写前台名称',
        'shop_logo.required' => '请上传前台logo',
        'pc_logo.required' => '请上传PC端logo',
        'clarity_code.required' => '请输入统计代码片段',
    ];

    public function rules($scene = '')
    {
        $rules = [
            'name' => 'required|max:30',
            'web_favicon' => 'required',
            'web_logo' => 'required',
            'login_image' => 'required',
            'shop_name' => 'required',
            'shop_logo' => 'required',
            'pc_logo' => 'required',
            'clarity_code' => 'required',
        ];

        $scenes = [
            'website' => ['name', 'web_favicon', 'web_logo', 'login_image', 'shop_name', 'shop_logo', 'pc_logo'],
            'siteStatistics' => ['clarity_code'],
        ];

        if (isset($scenes[$scene])) {
            return array_intersect_key($rules, array_flip($scenes[$scene]));
        }

        return $rules;
    }
}
