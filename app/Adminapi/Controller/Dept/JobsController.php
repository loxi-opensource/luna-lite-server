<?php

namespace App\Adminapi\Controller\Dept;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Lists\Dept\JobsLists;
use App\Adminapi\Logic\Dept\JobsLogic;
use App\Adminapi\Validate\Dept\JobsValidate;

/**
 * 岗位管理控制器
 */
class JobsController extends BaseAdminController
{
    /**
     * @notes 岗位列表
     */
    public function lists()
    {
        return $this->dataLists(new JobsLists());
    }

    /**
     * @notes 添加岗位
     */
    public function add()
    {
        $params = (new JobsValidate())->post()->goCheck('add');
        JobsLogic::add($params);
        return $this->success('添加成功', [], 1, 1);
    }

    /**
     * @notes 编辑岗位
     */
    public function edit()
    {
        $params = (new JobsValidate())->post()->goCheck('edit');
        $result = JobsLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(JobsLogic::getError());
    }

    /**
     * @notes 删除岗位
     */
    public function delete()
    {
        $params = (new JobsValidate())->post()->goCheck('delete');
        JobsLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }

    /**
     * @notes 获取岗位详情
     */
    public function detail()
    {
        $params = (new JobsValidate())->goCheck('detail');
        $result = JobsLogic::detail($params);
        return $this->data($result);
    }

    /**
     * @notes 获取岗位数据
     */
    public function all()
    {
        $result = JobsLogic::getAllData();
        return $this->data($result);
    }

}
