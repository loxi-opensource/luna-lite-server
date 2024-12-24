<?php

namespace App\Adminapi\Controller\Channel;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Logic\Channel\AppSettingLogic;

/**
 * APP设置控制器
 */
class AppSettingController extends BaseAdminController
{

    /**
     * @notes 获取App设置
     */
    public function getConfig()
    {
        $result = AppSettingLogic::getConfig();
        return $this->data($result);
    }


    /**
     * @notes App设置
     */
    public function setConfig()
    {
        $params = $this->request->post();
        AppSettingLogic::setConfig($params);
        return $this->success('操作成功', [], 1, 1);
    }
}
