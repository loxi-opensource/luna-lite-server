<?php

namespace App\Common\Service\Wechat;

use App\Common\Enum\PayEnum;
use App\Common\Enum\User\UserTerminalEnum;
use App\Common\Model\Pay\PayConfig;
use App\Common\Service\ConfigService;

/**
 * 微信配置类
 */
class WechatConfigService
{
    /**
     * @notes 获取小程序配置
     * @return array
     */
    public static function getMnpConfig()
    {
        return [
            'app_id' => ConfigService::get('mnp_setting', 'app_id'),
            'secret' => ConfigService::get('mnp_setting', 'app_secret'),
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => storage_path('wechat' . DIRECTORY_SEPARATOR . date('Ym') . DIRECTORY_SEPARATOR . date('d') . '.log'),
            ],
        ];
    }


    /**
     * @notes 获取微信公众号配置
     * @return array
     */
    public static function getOaConfig()
    {
        return [
            'app_id' => ConfigService::get('oa_setting', 'app_id'),
            'secret' => ConfigService::get('oa_setting', 'app_secret'),
            'token' => ConfigService::get('oa_setting', 'token'),
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => storage_path('wechat' . DIRECTORY_SEPARATOR . date('Ym') . DIRECTORY_SEPARATOR . date('d') . '.log'),
            ],
        ];
    }


    /**
     * @notes 获取微信开放平台配置
     * @return array
     */
    public static function getOpConfig()
    {
        return [
            'app_id' => ConfigService::get('open_platform', 'app_id'),
            'secret' => ConfigService::get('open_platform', 'app_secret'),
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => storage_path('wechat' . DIRECTORY_SEPARATOR . date('Ym') . DIRECTORY_SEPARATOR . date('d') . '.log'),
            ],
        ];
    }


    /**
     * @notes 根据终端获取支付配置
     * @param $terminal
     * @return array
     */
    public static function getPayConfigByTerminal($terminal)
    {
        switch ($terminal) {
            case UserTerminalEnum::WECHAT_MMP:
                $notifyUrl = route('pay.notifyMnp');
                break;
            case UserTerminalEnum::WECHAT_OA:
            case UserTerminalEnum::PC:
            case UserTerminalEnum::H5:
                $notifyUrl = route('pay.notifyOa');
                break;
            case UserTerminalEnum::ANDROID:
            case UserTerminalEnum::IOS:
                // todo Likeadmin源码没有对应的控制器方法
                $notifyUrl = route('pay.notifyApp');
                break;
        }

        $pay = PayConfig::where(['pay_way' => PayEnum::WECHAT_PAY])->first()->toArray();

        //判断是否已经存在证书文件夹，不存在则新建
        if (!file_exists(storage_path('cert'))) {
            mkdir(storage_path('cert'), 0775, true);
        }

        //写入文件
        $apiclientCert = $pay['config']['apiclient_cert'] ?? '';
        $apiclientKey = $pay['config']['apiclient_key'] ?? '';

        $certPath = storage_path('cert' . DIRECTORY_SEPARATOR . md5($apiclientCert) . '.pem');
        $keyPath = storage_path('cert' . DIRECTORY_SEPARATOR . md5($apiclientKey) . '.pem');

        if (!empty($apiclientCert) && !file_exists($certPath)) {
            static::setCert($certPath, trim($apiclientCert));
        }
        if (!empty($apiclientKey) && !file_exists($keyPath)) {
            static::setCert($keyPath, trim($apiclientKey));
        }

        // todo 支付参数未配置时抛出异常

        return [
            // 商户号
            'mch_id' => $pay['config']['mch_id'] ?? '',
            // 商户证书
            'private_key' => $keyPath,
            'certificate' => $certPath,
            // v3 API 秘钥
            'secret_key' => $pay['config']['pay_sign_key'] ?? '',
            'notify_url' => $notifyUrl,
            'http' => [
                'throw' => true, // 状态码非 200、300 时是否抛出异常，默认为开启
                'timeout' => 5.0,
            ]
        ];
    }


    /**
     * @notes 临时写入证书
     * @param $path
     * @param $cert
     */
    public static function setCert($path, $cert)
    {
        $fopenPath = fopen($path, 'w');
        fwrite($fopenPath, $cert);
        fclose($fopenPath);
    }


}
