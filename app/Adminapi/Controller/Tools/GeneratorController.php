<?php

namespace App\Adminapi\Controller\Tools;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Lists\Tools\DataTableLists;
use App\Adminapi\Lists\Tools\GenerateTableLists;
use App\Adminapi\Logic\Tools\GeneratorLogic;
use App\Adminapi\Validate\Tools\EditTableValidate;
use App\Adminapi\Validate\Tools\GenerateTableValidate;

/**
 * 代码生成器控制器
 */
class GeneratorController extends BaseAdminController
{
    public array $notNeedLogin = ['download'];

    /**
     * @notes 获取数据库中所有数据表信息
     */
    public function dataTable()
    {
        return $this->dataLists(new DataTableLists());
    }

    /**
     * @notes 获取已选择的数据表
     */
    public function generateTable()
    {
        return $this->dataLists(new GenerateTableLists());
    }

    /**
     * @notes 选择数据表
     */
    public function selectTable()
    {
        $params = (new GenerateTableValidate())->post()->goCheck('select');
        $result = GeneratorLogic::selectTable($params, $this->getAdminId());
        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(GeneratorLogic::getError());
    }


    /**
     * @notes 生成代码
     */
    public function generate()
    {
        $params = (new GenerateTableValidate())->post()->goCheck('id');
        $result = GeneratorLogic::generate($params);
        if (false === $result) {
            return $this->fail(GeneratorLogic::getError());
        }
        return $this->success('操作成功', $result, 1, 1);
    }


    /**
     * @notes 下载文件
     */
    public function download()
    {
        $params = (new GenerateTableValidate())->get()->goCheck('download');
        $result = GeneratorLogic::download($params['file']);
        if (false === $result) {
            return $this->fail(GeneratorLogic::getError() ?: '下载失败');
        }
        return response()->download($result, 'likeadmin-curd.zip');
    }


    /**
     * @notes 预览代码
     */
    public function preview()
    {
        $params = (new GenerateTableValidate())->post()->goCheck('id');
        $result = GeneratorLogic::preview($params);
        if (false === $result) {
            return $this->fail(GeneratorLogic::getError());
        }
        return $this->data($result);
    }


    /**
     * @notes 同步字段
     */
    public function syncColumn()
    {
        $params = (new GenerateTableValidate())->post()->goCheck('id');
        $result = GeneratorLogic::syncColumn($params);
        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(GeneratorLogic::getError());
    }


    /**
     * @notes 编辑表信息
     */
    public function edit()
    {
        $params = (new EditTableValidate())->post()->goCheck();
        $result = GeneratorLogic::editTable($params);
        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(GeneratorLogic::getError());
    }


    /**
     * @notes 获取已选择的数据表详情
     */
    public function detail()
    {
        $params = (new GenerateTableValidate())->goCheck('id');
        $result = GeneratorLogic::getTableDetail($params);
        return $this->success('', $result);
    }


    /**
     * @notes 删除已选择的数据表信息
     */
    public function delete()
    {
        $params = (new GenerateTableValidate())->post()->goCheck('id');
        $result = GeneratorLogic::deleteTable($params);
        if (true === $result) {
            return $this->success('操作成功', [], 1, 1);
        }
        return $this->fail(GeneratorLogic::getError());
    }


    /**
     * @notes 获取模型
     */
    public function getModels()
    {
        $result = GeneratorLogic::getAllModels();
        return $this->success('', $result, 1, 1);
    }

}

