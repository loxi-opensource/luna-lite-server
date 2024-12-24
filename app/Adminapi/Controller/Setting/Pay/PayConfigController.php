<?php

namespace App\Adminapi\Controller\Setting\Pay;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Lists\Setting\Pay\PayConfigLists;
use App\Adminapi\Logic\Setting\Pay\PayConfigLogic;
use App\Adminapi\Validate\Setting\PayConfigValidate;

/**
 * 支付配置
 */
class PayConfigController extends BaseAdminController
{
    /**
     * @notes 设置支付配置
     */
    public function setConfig()
    {
        $params = (new PayConfigValidate())->post()->goCheck();
        PayConfigLogic::setConfig($params);
        return $this->success('设置成功', [], 1, 1);
    }


    /**
     * @notes 获取支付配置
     */
    public function getConfig()
    {
        $id = (new PayConfigValidate())->goCheck('get');
        $result = PayConfigLogic::getConfig($id);
        return $this->success('获取成功', $result);
    }


    /**
     * @notes 获取支付配置列表
     */
    public function lists()
    {
        return $this->dataLists(new PayConfigLists());
    }
}
