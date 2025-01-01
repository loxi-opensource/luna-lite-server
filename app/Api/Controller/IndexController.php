<?php

namespace App\Api\Controller;

use App\Api\Logic\IndexLogic;

class IndexController extends BaseApiController
{

    public array $notNeedLogin = ['index', 'config', 'policy', 'decorate'];

    /**
     * @notes 首页数据
     */
    public function index()
    {
        $result = IndexLogic::getIndexData();
        return $this->data($result);
    }


    /**
     * @notes 全局配置
     */
    public function config()
    {
        $result = IndexLogic::getConfigData();
        return $this->data($result);
    }


    /**
     * @notes 政策协议
     */
    public function policy()
    {
        $type = $this->request->get('type', '');
        $result = IndexLogic::getPolicyByType($type);
        return $this->data($result);
    }


    /**
     * @notes 装修信息
     */
    public function decorate()
    {
        $id = $this->request->get('id');
        $result = IndexLogic::getDecorate($id);
        return $this->data($result);
    }

}
