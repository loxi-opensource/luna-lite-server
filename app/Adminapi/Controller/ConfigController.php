<?php

namespace App\Adminapi\Controller;

use App\Adminapi\Logic\ConfigLogic;

/**
 * 配置控制器
 */
class ConfigController extends BaseAdminController
{
    public array $notNeedLogin = ['getConfig', 'dict'];

    /**
     * @notes 基础配置
     */
    public function getConfig()
    {
        $data = ConfigLogic::getConfig();
        return $this->data($data);
    }

    /**
     * @notes 根据类型获取字典数据
     */
    public function dict()
    {
        $type = $this->request->get('type', '');
        $data = ConfigLogic::getDictByType($type);
        return $this->data($data);
    }

}
