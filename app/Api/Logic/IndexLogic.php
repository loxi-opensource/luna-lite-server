<?php

namespace App\Api\Logic;

use App\Common\Logic\BaseLogic;
use App\Common\Model\Article\Article;
use App\Common\Model\Decorate\DecoratePage;
use App\Common\Model\Decorate\DecorateTabbar;
use App\Common\Service\ConfigService;
use App\Common\Service\FileService;

class IndexLogic extends BaseLogic
{
    /**
     * @notes 首页数据
     */
    public static function getIndexData()
    {
        // 装修配置
        $decoratePage = DecoratePage::find(1);

        // 首页文章
        $field = [
            'id', 'title', 'desc', 'abstract', 'image', 'cid',
            'author', 'click_actual', 'click_virtual', 'create_time'
        ];

        $article = Article::query()
            ->select($field)
            ->where('is_show', 1)
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get()
            ->toArray();

        return [
            'page' => $decoratePage,
            'article' => $article
        ];
    }


    /**
     * @notes 获取政策协议
     * @param string $type
     * @return array
     */
    public static function getPolicyByType(string $type)
    {
        return [
            'title' => ConfigService::get('agreement', $type . '_title', ''),
            'content' => ConfigService::get('agreement', $type . '_content', ''),
        ];
    }


    /**
     * @notes 装修信息
     * @param $id
     * @return array
     */
    public static function getDecorate($id)
    {
        return DecoratePage::query()->select(['type', 'name', 'data', 'meta'])
            ->find($id)->toArray();
    }


    /**
     * @notes 获取配置
     * @return array
     */
    public static function getConfigData()
    {
        // 底部导航
        $tabbar = DecorateTabbar::getTabbarLists();
        // 导航颜色
        $style = ConfigService::get('tabbar', 'style', config('project.decorate.tabbar_style'));
        // 登录配置
        $loginConfig = [
            // 登录方式
            'login_way' => ConfigService::get('login', 'login_way', config('project.login.login_way')),
            // 注册强制绑定手机
            'coerce_mobile' => ConfigService::get('login', 'coerce_mobile', config('project.login.coerce_mobile')),
            // 政策协议
            'login_agreement' => ConfigService::get('login', 'login_agreement', config('project.login.login_agreement')),
            // 第三方登录 开关
            'third_auth' => ConfigService::get('login', 'third_auth', config('project.login.third_auth')),
            // 微信授权登录
            'wechat_auth' => ConfigService::get('login', 'wechat_auth', config('project.login.wechat_auth')),
            // qq授权登录
            'qq_auth' => ConfigService::get('login', 'qq_auth', config('project.login.qq_auth')),
        ];
        // 网址信息
        $website = [
            'h5_favicon' => FileService::getFileUrl(ConfigService::get('website', 'h5_favicon')),
            'shop_name' => ConfigService::get('website', 'shop_name'),
            'shop_logo' => FileService::getFileUrl(ConfigService::get('website', 'shop_logo')),
        ];
        // H5配置
        $domain = request()->schemeAndHttpHost();
        $webPage = [
            // 渠道状态 0-关闭 1-开启
            'status' => ConfigService::get('web_page', 'status', 1),
            // 关闭后渠道后访问页面 0-空页面 1-自定义链接
            'page_status' => ConfigService::get('web_page', 'page_status', 0),
            // 自定义链接
            'page_url' => ConfigService::get('web_page', 'page_url', ''),
            'url' => $domain . '/mobile',
        ];

        // 备案信息
        $copyright = ConfigService::get('copyright', 'config', []);

        return [
            'domain' => FileService::getFileUrl(),
            'style' => $style,
            'tabbar' => $tabbar,
            'login' => $loginConfig,
            'website' => $website,
            'webPage' => $webPage,
            'version' => config('project.version'),
            'copyright' => $copyright,
        ];
    }

}
