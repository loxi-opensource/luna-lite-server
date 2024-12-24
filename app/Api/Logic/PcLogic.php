<?php

namespace App\Api\Logic;

use App\Common\Enum\YesNoEnum;
use App\Common\Logic\BaseLogic;
use App\Common\Model\Article\Article;
use App\Common\Model\Article\ArticleCate;
use App\Common\Model\Article\ArticleCollect;
use App\Common\Model\Decorate\DecoratePage;
use App\Common\Service\ConfigService;
use App\Common\Service\FileService;

class PcLogic extends BaseLogic
{
    /**
     * @notes 首页数据
     */
    public static function getIndexData()
    {
        // 装修配置
        $decoratePage = DecoratePage::query()->find(4);
        // 最新资讯
        $newArticle = self::getLimitArticle('new', 7);
        // 全部资讯
        $allArticle = self::getLimitArticle('all', 5);
        // 热门资讯
        $hotArticle = self::getLimitArticle('hot', 8);

        return [
            'page' => $decoratePage,
            'all' => $allArticle,
            'new' => $newArticle,
            'hot' => $hotArticle
        ];
    }


    /**
     * @notes 获取文章
     */
    public static function getLimitArticle(string $sortType, int $limit = 0, int $cate = 0, int $excludeId = 0)
    {
        // 查询字段
        $fields = [
            'id', 'cid', 'title', 'desc', 'abstract', 'image',
            'author', 'click_actual', 'click_virtual', 'create_time'
        ];

        // 排序条件
        $orderBy = match ($sortType) {
            'new' => 'id DESC',
            'hot' => 'click_actual + click_virtual DESC, id DESC',
            default => 'sort DESC, id DESC',
        };

        // 查询条件
        $query = Article::query()->select($fields)
            ->where('is_show', YesNoEnum::YES);

        if ($cate) {
            $query->where('cid', $cate);
        }

        if ($excludeId) {
            $query->where('id', '<>', $excludeId);
        }

        // 添加点击数
        $query->orderByRaw($orderBy);

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get()->toArray();
    }


    /**
     * @notes 获取配置
     */
    public static function getConfigData()
    {
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

        // 网站信息
        $website = [
            'shop_name' => ConfigService::get('website', 'shop_name'),
            'shop_logo' => FileService::getFileUrl(ConfigService::get('website', 'shop_logo')),
            'pc_logo' => FileService::getFileUrl(ConfigService::get('website', 'pc_logo')),
            'pc_title' => ConfigService::get('website', 'pc_title'),
            'pc_ico' => FileService::getFileUrl(ConfigService::get('website', 'pc_ico')),
            'pc_desc' => ConfigService::get('website', 'pc_desc'),
            'pc_keywords' => ConfigService::get('website', 'pc_keywords'),
        ];

        // 站点统计
        $siteStatistics = [
            'clarity_code' => ConfigService::get('siteStatistics', 'clarity_code'),
        ];

        // 备案信息
        $copyright = ConfigService::get('copyright', 'config', []);

        // 公众号二维码
        $oaQrCode = ConfigService::get('oa_setting', 'qr_code', '');
        $oaQrCode = empty($oaQrCode) ? $oaQrCode : FileService::getFileUrl($oaQrCode);
        // 小程序二维码
        $mnpQrCode = ConfigService::get('mnp_setting', 'qr_code', '');
        $mnpQrCode = empty($mnpQrCode) ? $mnpQrCode : FileService::getFileUrl($mnpQrCode);

        return [
            'domain' => FileService::getFileUrl(),
            'login' => $loginConfig,
            'website' => $website,
            'siteStatistics' => $siteStatistics,
            'version' => config('project.version'),
            'copyright' => $copyright,
            'admin_url' => request()->schemeAndHttpHost() . '/admin',
            'qrcode' => [
                'oa' => $oaQrCode,
                'mnp' => $mnpQrCode,
            ]
        ];
    }


    /**
     * @notes 资讯中心
     */
    public static function getInfoCenter()
    {
        $data = ArticleCate::query()->select(['id', 'name', 'is_show'])
            ->with(['article' => function ($query) {
                $query->select(['id', 'cid', 'title', 'sort', 'click_actual', 'click_virtual', 'image'])
                    ->orderByDesc('sort')
                    ->orderByDesc('id')
                    ->limit(10);
            }])
            ->where('is_show', YesNoEnum::YES)
            ->orderByDesc('sort')
            ->orderByDesc('id')
            ->get()
            ->toArray();

        return $data;
    }


    /**
     * @notes 获取文章详情
     */
    public static function getArticleDetail($userId, $articleId, $source = 'default')
    {
        // 文章详情
        $detail = Article::getArticleDetailArr($articleId);

        // 根据来源列表查找对应列表
        $nowIndex = 0;
        $lists = self::getLimitArticle($source, 0, $detail['cid']);
        foreach ($lists as $key => $item) {
            if ($item['id'] == $articleId) {
                $nowIndex = $key;
            }
        }
        // 上一篇
        $detail['last'] = $lists[$nowIndex - 1] ?? [];
        // 下一篇
        $detail['next'] = $lists[$nowIndex + 1] ?? [];

        // 最新资讯
        $detail['new'] = self::getLimitArticle('new', 8, $detail['cid'], $detail['id']);
        // 关注状态
        $detail['collect'] = ArticleCollect::isCollectArticle($userId, $articleId);
        // 分类名
        $detail['cate_name'] = ArticleCate::query()->where('id', $detail['cid'])->value('name');

        return $detail;
    }

}
