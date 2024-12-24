<?php

namespace App\Adminapi\Controller\Decorate;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Logic\Decorate\DecorateDataLogic;

/**
 * 装修-数据
 */
class DataController extends BaseAdminController
{
    /**
     * @notes 文章列表
     */
    public function article()
    {
        $limit = $this->request->get('limit', 10);
        $result = DecorateDataLogic::getArticleLists($limit);
        return $this->success('获取成功', $result);
    }

    /**
     * @notes pc设置
     */
    public function pc()
    {
        $result = DecorateDataLogic::pc();
        return $this->data($result);
    }

}
