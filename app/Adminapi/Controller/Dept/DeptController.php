<?php

namespace App\Adminapi\Controller\Dept;


use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Logic\Dept\DeptLogic;
use App\Adminapi\Validate\Dept\DeptValidate;

/**
 * 部门管理控制器
 */
class DeptController extends BaseAdminController
{

    /**
     * @notes 部门列表
     */
    public function lists()
    {
        $params = $this->request->all();
        $result = DeptLogic::lists($params);
        return $this->success('', $result);
    }


    /**
     * @notes 上级部门
     */
    public function leaderDept()
    {
        $result = DeptLogic::leaderDept();
        return $this->success('', $result);
    }


    /**
     * @notes 添加部门
     */
    public function add()
    {
        $params = (new DeptValidate())->post()->goCheck('add');
        DeptLogic::add($params);
        return $this->success('添加成功', [], 1, 1);
    }


    /**
     * @notes 编辑部门
     */
    public function edit()
    {
        $params = (new DeptValidate())->post()->goCheck('edit');
        $result = DeptLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(DeptLogic::getError());
    }


    /**
     * @notes 删除部门
     */
    public function delete()
    {
        $params = (new DeptValidate())->post()->goCheck('delete');
        DeptLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取部门详情
     */
    public function detail()
    {
        $params = (new DeptValidate())->goCheck('detail');
        $result = DeptLogic::detail($params);
        return $this->data($result);
    }


    /**
     * @notes 获取部门数据
     */
    public function all()
    {
        $result = DeptLogic::getAllData();
        return $this->data($result);
    }


}
