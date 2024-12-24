<?php

namespace App\Api\Controller;

use App\Api\Lists\Recharge\RechargeLists;
use App\Api\Logic\RechargeLogic;
use App\Api\Validate\RechargeValidate;

/**
 * 充值控制器
 */
class RechargeController extends BaseApiController
{

    /**
     * @notes 获取充值列表
     */
    public function lists()
    {
        return $this->dataLists(new RechargeLists());
    }


    /**
     * @notes 充值
     */
    public function recharge()
    {
        $params = (new RechargeValidate())->post()->goCheck('recharge', [
            'user_id' => $this->getUserId(),
            'terminal' => $this->getUserInfo()['terminal'],
        ]);
        $result = RechargeLogic::recharge($params);
        if (false === $result) {
            return $this->fail(RechargeLogic::getError());
        }
        return $this->data($result);
    }

    /**
     * @notes 充值配置
     */
    public function config()
    {
        return $this->data(RechargeLogic::config($this->getUserId()));
    }


}
