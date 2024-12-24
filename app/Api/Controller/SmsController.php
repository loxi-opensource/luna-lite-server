<?php

namespace App\Api\Controller;

use App\Api\Logic\SmsLogic;
use App\Api\Validate\SendSmsValidate;

/**
 * 短信
 */
class SmsController extends BaseApiController
{

    public array $notNeedLogin = ['sendCode'];

    /**
     * @notes 发送短信验证码
     */
    public function sendCode()
    {
        $params = (new SendSmsValidate())->post()->goCheck();
        $result = SmsLogic::sendCode($params);
        if (true === $result) {
            return $this->success('发送成功');
        }
        return $this->fail(SmsLogic::getError());
    }

}
