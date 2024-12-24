<?php

namespace App\Adminapi\Controller\Channel;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Logic\Channel\OpenSettingLogic;
use App\Adminapi\Validate\Channel\OpenSettingValidate;


/**
 * 微信开放平台
 */
class OpenSettingController extends BaseAdminController
{

    /**
     * @notes 获取微信开放平台设置
     */
    public function getConfig()
    {
        $result = OpenSettingLogic::getConfig();
        return $this->data($result);
    }


    /**
     * @notes 微信开放平台设置
     */
    public function setConfig()
    {
        $params = (new OpenSettingValidate())->post()->goCheck();
        OpenSettingLogic::setConfig($params);
        return $this->success('操作成功', [], 1, 1);
    }
}
