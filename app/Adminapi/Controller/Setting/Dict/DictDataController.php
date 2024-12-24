<?php

namespace App\Adminapi\Controller\Setting\Dict;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Lists\Setting\Dict\DictDataLists;
use App\Adminapi\Logic\Setting\Dict\DictDataLogic;
use App\Adminapi\Validate\Dict\DictDataValidate;

/**
 * 字典数据
 */
class DictDataController extends BaseAdminController
{

    /**
     * @notes 获取字典数据列表
     */
    public function lists()
    {
        return $this->dataLists(new DictDataLists());
    }


    /**
     * @notes 添加字典数据
     */
    public function add()
    {
        $params = (new DictDataValidate())->post()->goCheck('add');
        DictDataLogic::save($params);
        return $this->success('添加成功', [], 1, 1);
    }


    /**
     * @notes 编辑字典数据
     */
    public function edit()
    {
        $params = (new DictDataValidate())->post()->goCheck('edit');
        DictDataLogic::save($params);
        return $this->success('编辑成功', [], 1, 1);
    }


    /**
     * @notes 删除字典数据
     */
    public function delete()
    {
        $params = (new DictDataValidate())->post()->goCheck('id');
        DictDataLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取字典详情
     */
    public function detail()
    {
        $params = (new DictDataValidate())->goCheck('id');
        $result = DictDataLogic::detail($params);
        return $this->data($result);
    }


}
