<?php

namespace App\Adminapi\Controller\Setting\User;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Logic\Setting\User\UserLogic;
use App\Adminapi\Validate\Setting\UserConfigValidate;

/**
 * 设置-用户设置控制器
 */
class UserController extends BaseAdminController
{

    /**
     * @notes 获取用户设置
     */
    public function getConfig()
    {
        $result = (new UserLogic())->getConfig();
        return $this->data($result);
    }


    /**
     * @notes 设置用户设置
     */
    public function setConfig()
    {
        $params = (new UserConfigValidate())->post()->goCheck('user');
        (new UserLogic())->setConfig($params);
        return $this->success('操作成功', [], 1, 1);
    }


    /**
     * @notes 获取注册配置
     */
    public function getRegisterConfig()
    {
        $result = (new UserLogic())->getRegisterConfig();
        return $this->data($result);
    }


    /**
     * @notes 设置注册配置
     */
    public function setRegisterConfig()
    {
        $params = (new UserConfigValidate())->post()->goCheck('register');
        (new UserLogic())->setRegisterConfig($params);
        return $this->success('操作成功', [], 1, 1);
    }

}
