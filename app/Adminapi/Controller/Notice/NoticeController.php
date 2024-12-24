<?php

namespace App\Adminapi\Controller\Notice;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Lists\Notice\NoticeSettingLists;
use App\Adminapi\Logic\Notice\NoticeLogic;
use App\Adminapi\Validate\Notice\NoticeValidate;

/**
 * 通知控制器
 */
class NoticeController extends BaseAdminController
{
    /**
     * @notes 查看通知设置列表
     */
    public function settingLists()
    {
        return $this->dataLists(new NoticeSettingLists());
    }


    /**
     * @notes 查看通知设置详情
     */
    public function detail()
    {
        $params = (new NoticeValidate())->goCheck('detail');
        $result = NoticeLogic::detail($params);
        return $this->data($result);
    }


    /**
     * @notes 通知设置
     */
    public function set()
    {
        $params = $this->request->post();
        $result = NoticeLogic::set($params);
        if ($result) {
            return $this->success('设置成功');
        }
        return $this->fail(NoticeLogic::getError());
    }
}
