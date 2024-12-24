<?php

namespace App\Adminapi\Controller\Setting\System;

use App\Adminapi\Controller\BaseAdminController;
use App\Adminapi\Lists\Setting\System\LogLists;

/**
 * 系统日志
 */
class LogController extends BaseAdminController
{
    public bool $shouldLogOperation = false;

    /**
     * @notes 查看系统日志列表
     */
    public function lists()
    {
        return $this->dataLists(new LogLists());
    }
}
