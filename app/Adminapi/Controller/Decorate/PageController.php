<?php

namespace App\Adminapi\Controller\Decorate;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Logic\Decorate\DecoratePageLogic;
use App\Adminapi\Validate\Decorate\DecoratePageValidate;

/**
 * 装修页面
 */
class PageController extends BaseAdminController
{
    /**
     * @notes 获取装修修页面详情
     */
    public function detail()
    {
        $id = $this->request->get('id');
        $result = DecoratePageLogic::getDetail($id);
        return $this->success('获取成功', $result);
    }

    /**
     * @notes 保存装修配置
     */
    public function save()
    {
        $params = (new DecoratePageValidate())->post()->goCheck();
        $result = DecoratePageLogic::save($params);
        if (false === $result) {
            return $this->fail(DecoratePageLogic::getError());
        }
        return $this->success('操作成功', [], 1, 1);
    }


}
