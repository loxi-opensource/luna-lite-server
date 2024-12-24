<?php

namespace App\Adminapi\Controller\Notice;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Logic\Notice\SmsConfigLogic;
use App\Adminapi\Validate\Notice\SmsConfigValidate;

/**
 * 短信配置控制器
 */
class SmsConfigController extends BaseAdminController
{

    /**
     * @notes 获取短信配置
     */
    public function getConfig()
    {
        $result = SmsConfigLogic::getConfig();
        return $this->data($result);
    }


    /**
     * @notes 短信配置
     */
    public function setConfig()
    {
        $params = (new SmsConfigValidate())->post()->goCheck('setConfig');
        SmsConfigLogic::setConfig($params);
        return $this->success('操作成功', [], 1, 1);
    }


    /**
     * @notes 查看短信配置详情
     */
    public function detail()
    {
        $params = (new SmsConfigValidate())->goCheck('detail');
        $result = SmsConfigLogic::detail($params);
        return $this->data($result);
    }

}
