<?php

namespace App\Adminapi\Logic;

use App\Common\Model\Dict\DictData;
use App\Common\Service\ConfigService;
use App\Common\Service\FileService;

/**
 * 配置类逻辑层
 */
class ConfigLogic
{
    /**
     * @notes 获取配置
     */
    public static function getConfig(): array
    {
        $config = [
            // 文件域名
            'oss_domain' => FileService::getFileUrl(),
            // 网站名称
            'web_name' => ConfigService::get('website', 'name'),
            // 网站图标
            'web_favicon' => FileService::getFileUrl(ConfigService::get('website', 'web_favicon')),
            // 网站logo
            'web_logo' => FileService::getFileUrl(ConfigService::get('website', 'web_logo')),
            // 登录页
            'login_image' => FileService::getFileUrl(ConfigService::get('website', 'login_image')),
            // 版权信息
            'copyright_config' => ConfigService::get('copyright', 'config', []),
            // 版本号
            'version' => config('project.version')
        ];
        return $config;
    }


    /**
     * @notes 根据类型获取字典类型
     * @param $type
     */
    public static function getDictByType($type)
    {
        if (!is_string($type)) {
            return [];
        }

        $type = explode(',', $type);
        $lists = DictData::query()->whereIn('type_value', $type)->get()->toArray();

        if (empty($lists)) {
            return [];
        }

        $result = [];
        foreach ($type as $item) {
            foreach ($lists as $dict) {
                if ($dict['type_value'] == $item) {
                    $result[$item][] = $dict;
                }
            }
        }
        return $result;
    }


}
