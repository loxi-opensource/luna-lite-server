<?php

namespace App\Adminapi\Controller\Setting\Dict;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Lists\Setting\Dict\DictTypeLists;
use App\Adminapi\Logic\Setting\Dict\DictTypeLogic;
use App\Adminapi\Validate\Dict\DictTypeValidate;

/**
 * 字典类型
 */
class DictTypeController extends BaseAdminController
{
    /**
     * @notes 获取字典类型列表
     */
    public function lists()
    {
        return $this->dataLists(new DictTypeLists());
    }


    /**
     * @notes 添加字典类型
     */
    public function add()
    {
        $params = (new DictTypeValidate())->post()->goCheck('add');
        DictTypeLogic::add($params);
        return $this->success('添加成功', [], 1, 1);
    }


    /**
     * @notes 编辑字典类型
     */
    public function edit()
    {
        $params = (new DictTypeValidate())->post()->goCheck('edit');
        DictTypeLogic::edit($params);
        return $this->success('编辑成功', [], 1, 1);
    }


    /**
     * @notes 删除字典类型
     */
    public function delete()
    {
        $params = (new DictTypeValidate())->post()->goCheck('delete');
        DictTypeLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取字典详情
     */
    public function detail()
    {
        $params = (new DictTypeValidate())->goCheck('detail');
        $result = DictTypeLogic::detail($params);
        return $this->data($result);
    }


    /**
     * @notes 获取字典类型数据
     */
    public function all()
    {
        $result = DictTypeLogic::getAllData();
        return $this->data($result);
    }


}
