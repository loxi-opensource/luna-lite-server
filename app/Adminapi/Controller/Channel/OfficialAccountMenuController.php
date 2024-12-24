<?php

namespace App\Adminapi\Controller\Channel;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Logic\Channel\OfficialAccountMenuLogic;

/**
 * 微信公众号菜单控制器
 */
class OfficialAccountMenuController extends BaseAdminController
{
    /**
     * @notes 保存菜单
     */
    public function save()
    {
        $params = $this->request->post();
        $result = OfficialAccountMenuLogic::save($params);
        if (false === $result) {
            return $this->fail(OfficialAccountMenuLogic::getError());
        }
        return $this->success('保存成功', [], 1, 1);
    }


    /**
     * @notes 保存发布菜单
     */
    public function saveAndPublish()
    {
        $params = $this->request->post();
        $result = OfficialAccountMenuLogic::saveAndPublish($params);
        if ($result) {
            return $this->success('保存并发布成功', [], 1, 1);
        }
        return $this->fail(OfficialAccountMenuLogic::getError());
    }


    /**
     * @notes 查看菜单详情
     */
    public function detail()
    {
        $result = OfficialAccountMenuLogic::detail();
        return $this->data($result);
    }
}
