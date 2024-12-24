<?php

namespace App\Adminapi\Controller\Auth;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Lists\Auth\AdminLists;
use App\Adminapi\Logic\Auth\AdminLogic;
use App\Adminapi\Validate\Auth\AdminValidate;

/**
 * 管理员控制器
 */
class AdminController extends BaseAdminController
{

    /**
     * @notes 查看管理员列表
     */
    public function lists()
    {
        return $this->dataLists(new AdminLists());
    }


    /**
     * @notes 添加管理员
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/12/29 10:21
     */
    public function add()
    {
        $params = (new AdminValidate())->goCheck('add');
        $result = AdminLogic::add($params);
        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(AdminLogic::getError());
    }

    /**
     * @notes 编辑管理员
     */
    public function edit()
    {
        $params = (new AdminValidate())->post()->goCheck('edit');
        $result = AdminLogic::edit($params);
        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(AdminLogic::getError());
    }


    /**
     * @notes 删除管理员
     */
    public function delete()
    {
        $params = (new AdminValidate())->post()->goCheck('delete');
        $result = AdminLogic::delete($params);
        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(AdminLogic::getError());
    }


    /**
     * @notes 查看管理员详情
     */
    public function detail()
    {
        $params = (new AdminValidate())->goCheck('detail');
        $result = AdminLogic::detail($params);
        return $this->data($result);
    }


    /**
     * @notes 获取当前管理员信息
     */
    public function mySelf()
    {
        $result = AdminLogic::detail(['id' => $this->getAdminId()], 'auth');
        return $this->data($result);
    }


    /**
     * @notes 编辑超级管理员信息
     */
    public function editSelf()
    {
        $params = (new AdminValidate())->goCheck('editSelf', ['admin_id' => $this->getAdminId()]);
        $result = AdminLogic::editSelf($params);
        return $this->success('操作成功', [], 1, 1);
    }

}
