<?php

namespace App\Adminapi\Logic\Channel;

use App\Common\Logic\BaseLogic;
use App\Common\Service\ConfigService;


/**
 * App设置逻辑层
 */
class AppSettingLogic extends BaseLogic
{
    /**
     * @notes 获取App设置
     */
    public static function getConfig()
    {
        $config = [
            'ios_download_url' => ConfigService::get('app', 'ios_download_url', ''),
            'android_download_url' => ConfigService::get('app', 'android_download_url', ''),
            'download_title' => ConfigService::get('app', 'download_title', ''),
        ];
        return $config;
    }


    /**
     * @notes App设置
     */
    public static function setConfig($params)
    {
        ConfigService::set('app', 'ios_download_url', $params['ios_download_url'] ?? '');
        ConfigService::set('app', 'android_download_url', $params['android_download_url'] ?? '');
        ConfigService::set('app', 'download_title', $params['download_title'] ?? '');
    }
}
