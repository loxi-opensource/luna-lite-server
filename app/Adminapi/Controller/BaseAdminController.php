<?php

namespace App\Adminapi\Controller;

use App\Common\Controller\BaseLikeAdminController;

class BaseAdminController extends BaseLikeAdminController
{
    /**
     * @var bool 是否记录操作日志
     */
    public bool $shouldLogOperation = true;

    public function getAdminId()
    {
        return $this->request->attributes->get('adminId');
    }

    public function getAdminInfo()
    {
        return $this->request->attributes->get('adminInfo');
    }

}
