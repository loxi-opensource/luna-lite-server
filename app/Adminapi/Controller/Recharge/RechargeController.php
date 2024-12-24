<?php

namespace App\Adminapi\Controller\Recharge;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Lists\Recharge\RechargeLists;
use App\Adminapi\Logic\Recharge\RechargeLogic;
use App\Adminapi\Validate\Recharge\RechargeRefundValidate;

/**
 * 充值控制器
 */
class RechargeController extends BaseAdminController
{

    /**
     * @notes 获取充值设置
     */
    public function getConfig()
    {
        $result = RechargeLogic::getConfig();
        return $this->data($result);
    }


    /**
     * @notes 充值设置
     */
    public function setConfig()
    {
        $params = $this->request->post();
        $result = RechargeLogic::setConfig($params);
        if ($result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(RechargeLogic::getError());
    }


    /**
     * @notes 充值记录
     */
    public function lists()
    {
        return $this->dataLists(new RechargeLists());
    }

    /**
     * @notes 退款
     */
    public function refund()
    {
        $params = (new RechargeRefundValidate())->post()->goCheck('refund');
        $result = RechargeLogic::refund($params, $this->getAdminId());
        list($flag, $msg) = $result;
        if (false === $flag) {
            return $this->fail($msg);
        }
        return $this->success($msg, [], 1, 1);
    }


    /**
     * @notes 重新退款
     */
    public function refundAgain()
    {
        $params = (new RechargeRefundValidate())->post()->goCheck('again');
        $result = RechargeLogic::refundAgain($params, $this->getAdminId());
        list($flag, $msg) = $result;
        if (false === $flag) {
            return $this->fail($msg);
        }
        return $this->success($msg, [], 1, 1);
    }

}
