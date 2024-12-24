<?php

namespace App\Adminapi\Logic\Decorate;

use App\Common\Logic\BaseLogic;
use App\Common\Model\Decorate\DecorateTabbar;
use App\Common\Service\ConfigService;
use App\Common\Service\FileService;
use Throwable;

/**
 * 装修配置-底部导航
 */
class DecorateTabbarLogic extends BaseLogic
{
    /**
     * @notes 获取底部导航详情
     */
    public static function detail(): array
    {
        $list = DecorateTabbar::getTabbarLists(); // Assuming this method exists on your model
        $style = ConfigService::get('tabbar', 'style', config('project.decorate.tabbar_style'));
        return ['style' => $style, 'list' => $list];
    }

    /**
     * @notes 底部导航保存
     */
    public static function save(array $params): bool
    {
        try {
            DecorateTabbar::where('id', '>', 0)->delete();
            $data = collect($params['list'] ?? [])
                ->map(function ($item) {
                    return [
                        'name' => $item['name'],
                        'selected' => FileService::setFileUrl($item['selected']),
                        'unselected' => FileService::setFileUrl($item['unselected']),
                        'link' => json_encode($item['link'], JSON_UNESCAPED_UNICODE),
                        'is_show' => $item['is_show'] ?? 0,
                    ];
                })
                ->toArray();

            DecorateTabbar::insert($data);

            if (!empty($params['style'])) {
                ConfigService::set('tabbar', 'style', $params['style']);
            }

            return true;
        } catch (Throwable $e) {
            self::setError($e->getMessage());
            return false;
        }
    }
}
