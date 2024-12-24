<?php

namespace App\Adminapi\Logic\Channel;

use App\Common\Logic\BaseLogic;
use App\Common\Service\ConfigService;

/**
 * H5设置逻辑层
 */
class WebPageSettingLogic extends BaseLogic
{
    /**
     * @notes 获取H5设置
     */
    public static function getConfig()
    {
        $config = [
            // 渠道状态 0-关闭 1-开启
            'status' => ConfigService::get('web_page', 'status', 1),
            // 关闭后渠道后访问页面 0-空页面 1-自定义链接
            'page_status' => ConfigService::get('web_page', 'page_status', 0),
            // 自定义链接
            'page_url' => ConfigService::get('web_page', 'page_url', ''),
            'url' => request()->schemeAndHttpHost() . '/mobile'
        ];
        return $config;
    }


    /**
     * @notes H5设置
     */
    public static function setConfig($params)
    {
        ConfigService::set('web_page', 'status', $params['status']);
        ConfigService::set('web_page', 'page_status', $params['page_status']);
        ConfigService::set('web_page', 'page_url', $params['page_url']);
    }
}
