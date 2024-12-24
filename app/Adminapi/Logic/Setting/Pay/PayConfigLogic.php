<?php

namespace App\Adminapi\Logic\Setting\Pay;

use App\Common\Enum\PayEnum;
use App\Common\Logic\BaseLogic;
use App\Common\Model\Pay\PayConfig;
use App\Common\Service\FileService;

/**
 * 支付配置
 */
class PayConfigLogic extends BaseLogic
{

    /**
     * @notes 设置配置
     */
    public static function setConfig($params)
    {
        $payConfig = PayConfig::findOrFail($params['id']);

        $config = [];
        if ($payConfig['pay_way'] == PayEnum::WECHAT_PAY) {
            $config = [
                'interface_version' => $params['config']['interface_version'],
                'merchant_type' => $params['config']['merchant_type'],
                'mch_id' => $params['config']['mch_id'],
                'pay_sign_key' => $params['config']['pay_sign_key'],
                'apiclient_cert' => $params['config']['apiclient_cert'],
                'apiclient_key' => $params['config']['apiclient_key'],
            ];
        }
        if ($payConfig['pay_way'] == PayEnum::ALI_PAY) {
            $config = [
                'mode' => $params['config']['mode'],
                'merchant_type' => $params['config']['merchant_type'],
                'app_id' => $params['config']['app_id'],
                'private_key' => $params['config']['private_key'],
                'ali_public_key' => $params['config']['mode'] == 'normal_mode' ? $params['config']['ali_public_key'] : '',
                'public_cert' => $params['config']['mode'] == 'certificate' ? $params['config']['public_cert'] : '',
                'ali_public_cert' => $params['config']['mode'] == 'certificate' ? $params['config']['ali_public_cert'] : '',
                'ali_root_cert' => $params['config']['mode'] == 'certificate' ? $params['config']['ali_root_cert'] : '',
            ];
        }

        $payConfig->name = $params['name'];
        $payConfig->icon = FileService::setFileUrl($params['icon']);
        $payConfig->sort = $params['sort'];
        $payConfig->config = $config;
        $payConfig->remark = $params['remark'] ?? '';
        return $payConfig->save();
    }


    /**
     * @notes 获取配置
     */
    public static function getConfig($params)
    {
        $payConfig = PayConfig::findOrFail($params['id'])->toArray();
        $payConfig['icon'] = FileService::getFileUrl($payConfig['icon']);
        $payConfig['domain'] = request()->getSchemeAndHttpHost();
        return $payConfig;
    }

}
