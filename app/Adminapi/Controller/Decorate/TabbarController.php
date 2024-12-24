<?php

namespace App\Adminapi\Controller\Decorate;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Logic\Decorate\DecorateTabbarLogic;

/**
 * 装修-底部导航
 */
class TabbarController extends BaseAdminController
{
    /**
     * @notes 底部导航详情
     */
    public function detail()
    {
        $data = DecorateTabbarLogic::detail();
        return $this->success('', $data);
    }


    /**
     * @notes 底部导航保存
     */
    public function save()
    {
        $params = $this->request->post();
        $result = DecorateTabbarLogic::save($params);
        if (false === $result) {
            return $this->fail(DecorateTabbarLogic::getError());
        }
        return $this->success('操作成功', [], 1, 1);
    }


}
