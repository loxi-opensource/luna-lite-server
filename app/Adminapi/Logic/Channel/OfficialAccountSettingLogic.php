<?php

namespace App\Adminapi\Logic\Channel;

use App\Common\Logic\BaseLogic;
use App\Common\Service\ConfigService;
use App\Common\Service\FileService;

/**
 * 公众号设置逻辑
 */
class OfficialAccountSettingLogic extends BaseLogic
{
    /**
     * @notes 获取公众号配置
     */
    public function getConfig()
    {
        $domainName = $_SERVER['SERVER_NAME'];
        $qrCode = ConfigService::get('oa_setting', 'qr_code', '');
        $qrCode = empty($qrCode) ? $qrCode : FileService::getFileUrl($qrCode);
        $config = [
            'name' => ConfigService::get('oa_setting', 'name', ''),
            'original_id' => ConfigService::get('oa_setting', 'original_id', ''),
            'qr_code' => $qrCode,
            'app_id' => ConfigService::get('oa_setting', 'app_id', ''),
            'app_secret' => ConfigService::get('oa_setting', 'app_secret', ''),
            'url' => route('channel.official_account_reply.index'),
            'token' => ConfigService::get('oa_setting', 'token'),
            'encoding_aes_key' => ConfigService::get('oa_setting', 'encoding_aes_key', ''),
            'encryption_type' => ConfigService::get('oa_setting', 'encryption_type', 1),
            'business_domain' => $domainName,
            'js_secure_domain' => $domainName,
            'web_auth_domain' => $domainName,
        ];
        return $config;
    }

    /**
     * @notes 设置公众号配置
     */
    public function setConfig($params)
    {
        $qrCode = isset($params['qr_code']) ? FileService::setFileUrl($params['qr_code']) : '';

        ConfigService::set('oa_setting', 'name', $params['name'] ?? '');
        ConfigService::set('oa_setting', 'original_id', $params['original_id'] ?? '');
        ConfigService::set('oa_setting', 'qr_code', $qrCode);
        ConfigService::set('oa_setting', 'app_id', $params['app_id']);
        ConfigService::set('oa_setting', 'app_secret', $params['app_secret']);
        ConfigService::set('oa_setting', 'token', $params['token'] ?? '');
        ConfigService::set('oa_setting', 'encoding_aes_key', $params['encoding_aes_key'] ?? '');
        ConfigService::set('oa_setting', 'encryption_type', $params['encryption_type']);
    }
}
