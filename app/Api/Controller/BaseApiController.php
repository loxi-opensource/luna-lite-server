<?php

namespace App\Api\Controller;

use App\Common\Controller\BaseLikeAdminController;

class BaseApiController extends BaseLikeAdminController
{
    protected function getUserId()
    {
        return $this->request->attributes->get('userId');
    }

    protected function getUserInfo()
    {
        return $this->request->attributes->get('userInfo');
    }
}
