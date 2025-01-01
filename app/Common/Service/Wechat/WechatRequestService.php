<?php
// +----------------------------------------------------------------------
// | Luna-Swapping开源AI换脸项目Lite版本（基于likeadmin_laravel框架）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | Github开源仓库：https://github.com/loxi-opensource/luna-lite-server
// | 全栈开发框架：https://github.com/1nFrastr/likeadmin_laravel
// | 独立开发者博客：https://www.sodair.top
// +----------------------------------------------------------------------
// | author: 1nFrastr x likeadminTeam
// +----------------------------------------------------------------------
namespace app\common\service\wechat;


use app\common\logic\BaseLogic;
use WpOrg\Requests\Requests;


/**
 * 自定义微信请求
 * Class WeChatRequestService
 * @package app\common\service\wechat
 */
class WechatRequestService extends BaseLogic
{

    /**
     * @notes 获取网站扫码登录地址
     * @param $appId
     * @param $redirectUri
     * @param $state
     * @return string
     * @author 段誉
     * @date 2022/10/20 18:20
     */
    public static function getScanCodeUrl($appId, $redirectUri, $state)
    {
        $url = 'https://open.weixin.qq.com/connect/qrconnect?';
        $url .= 'appid=' . $appId . '&redirect_uri=' . $redirectUri . '&response_type=code&scope=snsapi_login';
        $url .= '&state=' . $state . '#wechat_redirect';
        return $url;
    }


    /**
     * @notes 通过code获取用户信息(access_token,openid,unionid等)
     * @param $code
     * @return mixed
     * @author 段誉
     * @date 2022/10/21 10:16
     */
    public static function getUserAuthByCode($code)
    {
        $config = WechatConfigService::getOpConfig();
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
        $url .= '?appid=' . $config['app_id'] . '&secret=' . $config['secret'] . '&code=' . $code;
        $url .= '&grant_type=authorization_code';
        $requests = Requests::get($url);
        return json_decode($requests->body, true);
    }


    /**
     * @notes 通过授权信息获取用户信息
     * @param $accessToken
     * @param $openId
     * @return mixed
     * @author 段誉
     * @date 2022/10/21 10:21
     */
    public static function getUserInfoByAuth($accessToken, $openId)
    {
        $url = 'https://api.weixin.qq.com/sns/userinfo';
        $url .= '?access_token=' . $accessToken . '&openid=' . $openId;
        $response = Requests::get($url);
        return json_decode($response->body, true);
    }


}
