<?php

namespace App\Adminapi\Controller\Setting\Pay;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Logic\Setting\Pay\PayWayLogic;

/**
 * 支付方式
 */
class PayWayController extends BaseAdminController
{

    /**
     * @notes 获取支付方式
     */
    public function getPayWay()
    {
        $result = PayWayLogic::getPayWay();
        return $this->success('获取成功', $result);
    }


    /**
     * @notes 设置支付方式
     */
    public function setPayWay()
    {
        $params = $this->request->post();
        $result = (new PayWayLogic())->setPayWay($params);
        if (true !== $result) {
            return $this->fail($result);
        }
        return $this->success('操作成功', [], 1, 1);
    }
}
