<?php

namespace App\Adminapi\Controller\User;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Lists\User\UserLists;
use App\Adminapi\Logic\User\UserLogic;
use App\Adminapi\Validate\User\AdjustUserMoney;
use App\Adminapi\Validate\User\UserValidate;

/**
 * 用户控制器
 */
class UserController extends BaseAdminController
{

    /**
     * @notes 用户列表
     */
    public function lists()
    {
        return $this->dataLists(new UserLists());
    }


    /**
     * @notes 获取用户详情
     */
    public function detail()
    {
        $params = (new UserValidate())->goCheck('detail');
        $detail = UserLogic::detail($params['id']);
        return $this->success('', $detail);
    }


    /**
     * @notes 编辑用户信息
     */
    public function edit()
    {
        $params = (new UserValidate())->post()->goCheck('setInfo');
        UserLogic::setUserInfo($params);
        return $this->success('操作成功', [], 1, 1);
    }


    /**
     * @notes 调整用户余额
     */
    public function adjustMoney()
    {
        $params = (new AdjustUserMoney())->post()->goCheck();
        $res = UserLogic::adjustUserMoney($params);
        if (true === $res) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail($res);
    }

}
