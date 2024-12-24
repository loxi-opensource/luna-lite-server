<?php

namespace App\Api\Controller;


use App\Api\Logic\PcLogic;

/**
 * PC
 */
class PcController extends BaseApiController
{
    public array $notNeedLogin = ['index', 'config', 'infoCenter', 'articleDetail'];

    /**
     * @notes 首页数据
     */
    public function index()
    {
        $result = PcLogic::getIndexData();
        return $this->data($result);
    }

    /**
     * @notes 全局配置
     */
    public function config()
    {
        $result = PcLogic::getConfigData();
        return $this->data($result);
    }

    /**
     * @notes 资讯中心
     */
    public function infoCenter()
    {
        $result = PcLogic::getInfoCenter();
        return $this->data($result);
    }

    /**
     * @notes 获取文章详情
     */
    public function articleDetail()
    {
        $id = $this->request->get('id', 0);
        $source = $this->request->get('source', 'default');
        $result = PcLogic::getArticleDetail($this->getUserId(), $id, $source);
        return $this->data($result);
    }

}
