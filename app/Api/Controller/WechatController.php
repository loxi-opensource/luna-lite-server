<?php

namespace App\Api\Controller;

use App\Api\Logic\WechatLogic;
use App\Api\Validate\WechatValidate;

/**
 * 微信
 * Class WechatController
 */
class WechatController extends BaseApiController
{
    public array $notNeedLogin = ['jsConfig'];

    /**
     * @notes 微信JSSDK授权接口
     */
    public function jsConfig()
    {
        $params = (new WechatValidate())->goCheck('jsConfig');
        $result = WechatLogic::jsConfig($params);
        if ($result === false) {
            return $this->fail(WechatLogic::getError(), [], 0, 0);
        }
        return $this->data($result);
    }
}
