<?php

namespace App\Adminapi\Validate\Setting;

use App\Common\Enum\PayEnum;
use App\Common\Model\Pay\PayConfig;
use App\Common\Validate\BaseValidate;
use Closure;

class PayConfigValidate extends BaseValidate
{
    public function rules($scene = '')
    {
        $rules = [
            'get' => [
                'id' => 'required',
            ],
            'edit' => [
                'id' => 'required',
                'name' => [
                    'required',
                    function ($attribute, $value, Closure $fail) {
                        $result = PayConfig::where('name', $value)
                            ->where('id', '<>', request()->id)
                            ->first();
                        if ($result) {
                            return $fail('支付名称已存在');
                        }
                    },
                ],
                'icon' => 'required',
                'sort' => 'required|numeric|digits_between:1,5',
                'config' => [
                    function ($attribute, $value, Closure $fail) {
                        $payConfig = PayConfig::find(request()->id);
                        if (!$payConfig) {
                            return $fail('支付方式不存在');
                        }

                        if ($payConfig->pay_way != PayEnum::BALANCE_PAY && !isset($value)) {
                            return $fail('支付配置不能为空');
                        }

                        if ($payConfig->pay_way == PayEnum::WECHAT_PAY) {
                            if (empty($value['interface_version'])) {
                                return $fail('微信支付接口版本不能为空');
                            }
                            if (empty($value['merchant_type'])) {
                                return $fail('商户类型不能为空');
                            }
                            if (empty($value['mch_id'])) {
                                return $fail('微信支付商户号不能为空');
                            }
                            if (empty($value['pay_sign_key'])) {
                                return $fail('商户API密钥不能为空');
                            }
                            if (empty($value['apiclient_cert'])) {
                                return $fail('微信支付证书不能为空');
                            }
                            if (empty($value['apiclient_key'])) {
                                return $fail('微信支付证书密钥不能为空');
                            }
                        }

                        if ($payConfig->pay_way == PayEnum::ALI_PAY) {
                            if (empty($value['mode'])) {
                                return $fail('模式不能为空');
                            }
                            if (empty($value['merchant_type'])) {
                                return $fail('商户类型不能为空');
                            }
                            if (empty($value['app_id'])) {
                                return $fail('应用ID不能为空');
                            }
                            if (empty($value['private_key'])) {
                                return $fail('应用私钥不能为空');
                            }
                            if (empty($value['ali_public_key'])) {
                                return $fail('支付宝公钥不能为空');
                            }
                        }
                    },
                ],
            ],
        ];

        return $rules[$scene] ?? [];
    }

    protected $messages = [
        'id.required' => 'id不能为空',
        'name.required' => '支付名称不能为空',
        'icon.required' => '支付图标不能为空',
        'sort.required' => '排序不能为空',
        'sort.numeric' => '排序必须是纯数字',
        'sort.digits_between' => '排序最大不能超过五位数',
    ];

    public function messages()
    {
        return $this->messages;
    }
}
