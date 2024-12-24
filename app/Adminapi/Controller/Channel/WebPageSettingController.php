<?php

namespace App\Adminapi\Controller\Channel;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Logic\Channel\WebPageSettingLogic;
use App\Adminapi\Validate\Channel\WebPageSettingValidate;

/**
 * H5设置控制器
 */
class WebPageSettingController extends BaseAdminController
{

    /**
     * @notes 获取H5设置
     */
    public function getConfig()
    {
        $result = WebPageSettingLogic::getConfig();
        return $this->data($result);
    }


    /**
     * @notes H5设置
     */
    public function setConfig()
    {
        $params = (new WebPageSettingValidate())->post()->goCheck();
        WebPageSettingLogic::setConfig($params);
        return $this->success('操作成功', [], 1, 1);
    }
}
