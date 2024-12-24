<?php

namespace App\Adminapi\Controller;

use App\Adminapi\Logic\WorkbenchLogic;

/**
 * 工作台
 */
class WorkbenchController extends BaseAdminController
{
    /**
     * @notes 工作台
     */
    public function index()
    {
        $result = WorkbenchLogic::index();
        return $this->data($result);
    }
}
