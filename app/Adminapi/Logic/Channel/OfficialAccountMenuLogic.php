<?php

namespace App\Adminapi\Logic\Channel;

use App\Common\Enum\OfficialAccountEnum;
use App\Common\Logic\BaseLogic;
use App\Common\Service\ConfigService;
use App\Common\Service\Wechat\WechatOaService;


/**
 * 微信公众号菜单逻辑层
 */
class OfficialAccountMenuLogic extends BaseLogic
{
    /**
     * @notes 保存
     */
    public static function save($params)
    {
        try {
            self::checkMenu($params);
            ConfigService::set('oa_setting', 'menu', $params);
            return true;
        } catch (\Exception $e) {
            OfficialAccountMenuLogic::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 一级菜单校验
     */
    public static function checkMenu($menu)
    {
        if (empty($menu) || !is_array($menu)) {
            throw new \Exception('请设置正确格式菜单');
        }

        if (count($menu) > 3) {
            throw new \Exception('一级菜单超出限制(最多3个)');
        }

        foreach ($menu as $item) {
            if (!is_array($item)) {
                throw new \Exception('一级菜单项须为数组格式');
            }

            if (empty($item['name'])) {
                throw new \Exception('请输入一级菜单名称');
            }

            if (mb_strlen($item['name']) > 4) {
                throw new \Exception("一级菜单名称字数不能超过4个字符");
            }

            if (false == $item['has_menu']) {
                if (empty($item['type'])) {
                    throw new \Exception('一级菜单未选择菜单类型');
                }
                if (!in_array($item['type'], OfficialAccountEnum::MENU_TYPE)) {
                    throw new \Exception('一级菜单类型错误');
                }
                self::checkType($item);
            }

            if (true == $item['has_menu'] && empty($item['sub_button'])) {
                throw new \Exception('请配置子菜单');
            }

            if (!empty($item['sub_button'])) {
                self::checkSubButton($item['sub_button']);
            }
        }
    }


    /**
     * @notes 二级菜单校验
     */
    public static function checkSubButton($subButtion)
    {
        if (!is_array($subButtion)) {
            throw new \Exception('二级菜单须为数组格式');
        }

        if (count($subButtion) > 5) {
            throw new \Exception('二级菜单超出限制(最多5个)');
        }

        foreach ($subButtion as $subItem) {
            if (!is_array($subItem)) {
                throw new \Exception('二级菜单项须为数组');
            }

            if (empty($subItem['name'])) {
                throw new \Exception('请输入二级菜单名称');
            }

            if (mb_strlen($subItem['name']) > 8) {
                throw new \Exception("二级菜单名称字数不能超过8个字符");
            }

            if (empty($subItem['type']) || !in_array($subItem['type'], OfficialAccountEnum::MENU_TYPE)) {
                throw new \Exception('二级未选择菜单类型或菜单类型错误');
            }

            self::checkType($subItem);
        }
    }


    /**
     * @notes 菜单类型校验
     */
    public static function checkType($item)
    {
        switch ($item['type']) {
            // 关键字
            case 'click':
                if (empty($item['key'])) {
                    throw new \Exception('请输入关键字');
                }
                break;
            // 跳转网页链接
            case 'view':
                if (empty($item['url'])) {
                    throw new \Exception('请输入网页链接');
                }
                break;
            // 小程序
            case 'miniprogram':
                if (empty($item['url'])) {
                    throw new \Exception('请输入网页链接');
                }
                if (empty($item['appid'])) {
                    throw new \Exception('请输入appid');
                }
                if (empty($item['pagepath'])) {
                    throw new \Exception('请输入小程序路径');
                }
                break;
        }
    }

    /**
     * @notes 保存发布菜单
     */
    public static function saveAndPublish($params)
    {
        try {
            self::checkMenu($params);

            $result = (new WechatOaService())->createMenu($params);
            if ($result['errcode'] == 0) {
                ConfigService::set('oa_setting', 'menu', $params);
                return true;
            }

            self::setError('保存发布菜单失败' . json_encode($result->getContent()));

            return false;

        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 查看菜单详情
     */
    public static function detail()
    {
        $data = ConfigService::get('oa_setting', 'menu', []);

        if (!empty($data)) {
            foreach ($data as &$item) {
                $item['has_menu'] = !empty($item['has_menu']);
            }
        }

        return $data;
    }
}
