<?php

namespace App\Common\Service\Wechat;

use EasyWeChat\Kernel\Exceptions\Exception;
use EasyWeChat\OfficialAccount\Application;

/**
 * 公众号相关
 */
class WechatOaService
{

    protected $app;

    protected $config;


    public function __construct()
    {
        $this->config = $this->getConfig();
        $this->app = new Application($this->config);
    }


    /**
     * @notes easywechat服务端
     * @return \EasyWeChat\Kernel\Contracts\Server|\EasyWeChat\OfficialAccount\Server
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \ReflectionException
     * @throws \Throwable
     * @author 段誉
     * @date 2023/2/27 14:22
     */
    public function getServer()
    {
        return $this->app->getServer();
    }


    /**
     * @notes 配置
     * @return array
     * @throws Exception
     * @author 段誉
     * @date 2023/2/27 12:03
     */
    protected function getConfig()
    {
        $config = WechatConfigService::getOaConfig();
        if (empty($config['app_id']) || empty($config['secret'])) {
            throw new Exception('请先设置公众号配置');
        }
        return $config;
    }


    /**
     * @notes 公众号-根据code获取微信信息
     * @param string $code
     * @return mixed
     * @throws Exception
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @author 段誉
     * @date 2023/2/27 11:04
     */
    public function getOaResByCode(string $code)
    {
        $response = $this->app->getOAuth()
            ->scopes(['snsapi_userinfo'])
            ->userFromCode($code)
            ->getRaw();

        if (!isset($response['openid']) || empty($response['openid'])) {
            throw new Exception('获取openID失败');
        }

        return $response;
    }


    /**
     * @notes 公众号跳转url
     * @param string $url
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @author 段誉
     * @date 2023/2/27 10:35
     */
    public function getCodeUrl(string $url)
    {
        return $this->app->getOAuth()
            ->scopes(['snsapi_userinfo'])
            ->redirect($url);
    }


    /**
     * @notes 创建公众号菜单
     * @param array $buttons
     * @param array $matchRule
     * @return \EasyWeChat\Kernel\HttpClient\Response|\Symfony\Contracts\HttpClient\ResponseInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @author 段誉
     * @date 2023/2/27 12:07
     */
    public function createMenu(array $buttons, array $matchRule = [])
    {
        if (!empty($matchRule)) {
            return $this->app->getClient()->postJson('cgi-bin/menu/addconditional', [
                'button' => $buttons,
                'matchrule' => $matchRule,
            ]);
        }

        return $this->app->getClient()->postJson('cgi-bin/menu/create', ['button' => $buttons]);
    }


    /**
     * @notes 获取jssdkConfig
     * @param $url
     * @param $jsApiList
     * @param array $openTagList
     * @param false $debug
     * @return mixed[]
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @author 段誉
     * @date 2023/3/1 11:46
     */
    public function getJsConfig($url, $jsApiList, $openTagList = [], $debug = false)
    {
        return $this->app->getUtils()->buildJsSdkConfig($url, $jsApiList, $openTagList, $debug);
    }

}
