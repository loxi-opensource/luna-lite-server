<?php

namespace App\Api\Controller;

use App\Api\Logic\UserLogic;
use App\Api\Validate\PasswordValidate;
use App\Api\Validate\SetUserInfoValidate;
use App\Api\Validate\UserValidate;

/**
 * 用户控制器
 */
class UserController extends BaseApiController
{

    public array $notNeedLogin = ['resetPassword'];

    /**
     * @notes 获取个人中心
     */
    public function center()
    {
        $data = UserLogic::center($this->getUserInfo());
        return $this->success('', $data);
    }


    /**
     * @notes 获取个人信息
     */
    public function info()
    {
        $result = UserLogic::info($this->getUserId());
        return $this->data($result);
    }


    /**
     * @notes 重置密码
     */
    public function resetPassword()
    {
        $params = (new PasswordValidate())->post()->goCheck('resetPassword');
        $result = UserLogic::resetPassword($params);
        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(UserLogic::getError());
    }


    /**
     * @notes 修改密码
     */
    public function changePassword()
    {
        $params = (new PasswordValidate())->post()->goCheck('changePassword');
        $result = UserLogic::changePassword($params, $this->getUserId());
        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(UserLogic::getError());
    }


    /**
     * @notes 获取小程序手机号
     */
    public function getMobileByMnp()
    {
        $params = (new UserValidate())->post()->goCheck('getMobileByMnp');
        $params['user_id'] = $this->getUserId();
        $result = UserLogic::getMobileByMnp($params);
        if ($result === false) {
            return $this->fail(UserLogic::getError());
        }
        return $this->success('绑定成功', [], 1, 1);
    }


    /**
     * @notes 编辑用户信息
     */
    public function setInfo()
    {
        $params = (new SetUserInfoValidate())->post()->goCheck(null, ['user_id' => $this->getUserId()]);
        $result = UserLogic::setInfo($this->getUserId(), $params);
        if (false === $result) {
            return $this->fail(UserLogic::getError());
        }
        return $this->success('操作成功', [], 1, 1);
    }


    /**
     * @notes 绑定/变更 手机号
     */
    public function bindMobile()
    {
        $params = (new UserValidate())->post()->goCheck('bindMobile');
        $params['user_id'] = $this->getUserId();
        $result = UserLogic::bindMobile($params);
        if ($result) {
            return $this->success('绑定成功', [], 1, 1);
        }
        return $this->fail(UserLogic::getError());
    }

}
