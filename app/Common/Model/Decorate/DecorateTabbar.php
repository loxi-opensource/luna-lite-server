<?php

namespace App\Common\Model\Decorate;

use App\Common\Model\BaseModel;
use App\Common\Service\FileService;

/**
 * 装修配置-底部导航
 */
class DecorateTabbar extends BaseModel
{
    protected $table = 'decorate_tabbar';

    public $casts = [
        'link' => 'array',
    ];

    /**
     * @notes 获取底部导航列表
     * @return array
     */
    public static function getTabbarLists()
    {
        $tabbar = self::all()->toArray();

        if (empty($tabbar)) {
            return $tabbar;
        }

        foreach ($tabbar as &$item) {
            if (!empty($item['selected'])) {
                $item['selected'] = FileService::getFileUrl($item['selected']);
            }
            if (!empty($item['unselected'])) {
                $item['unselected'] = FileService::getFileUrl($item['unselected']);
            }
        }

        return $tabbar;
    }
}
