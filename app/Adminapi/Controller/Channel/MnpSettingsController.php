<?php

namespace App\Adminapi\Controller\Channel;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Logic\Channel\MnpSettingsLogic;
use App\Adminapi\Validate\Channel\MnpSettingsValidate;

/**
 * 小程序设置
 */
class MnpSettingsController extends BaseAdminController
{
    /**
     * @notes 获取小程序配置
     */
    public function getConfig()
    {
        $result = (new MnpSettingsLogic())->getConfig();
        return $this->data($result);
    }

    /**
     * @notes 设置小程序配置
     */
    public function setConfig()
    {
        $params = (new MnpSettingsValidate())->post()->goCheck();
        (new MnpSettingsLogic())->setConfig($params);
        return $this->success('操作成功', [], 1, 1);
    }
}
