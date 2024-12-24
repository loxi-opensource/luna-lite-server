<?php

namespace App\Common\Controller;

use App\Common\Lists\BaseDataLists;
use App\Common\Service\JsonService;
use Illuminate\Http\Request;

class BaseLikeAdminController
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public array $notNeedLogin = [];

    protected function success(string $msg = 'success', array $data = [], int $code = 1, int $show = 0)
    {
        return JsonService::success($msg, $data, $code, $show);
    }

    protected function data($data)
    {
        return JsonService::data($data);
    }

    protected function fail(string $msg = 'fail', array $data = [], int $code = 0, int $show = 1)
    {
        return JsonService::fail($msg, $data, $code, $show);
    }

    protected function dataLists(BaseDataLists $lists = null)
    {
        //当对象为空时，自动创建列表对象
        if (is_null($lists)) {
            $lists = app()->make($this->getListClassName());
        }
        return JsonService::dataLists($lists);
    }

    protected function getListClassName()
    {
        // 自动获取列表类名
        $controllerName = $this->request->route()->getControllerClass();
        $listClassName = str_replace('Controller', 'Lists', $controllerName);
        return $listClassName;
        //{
        //    "controllerName": "App\\Adminapi\\Controller\\Auth\\AdminController",
        //    "listClassName": "App\\Adminapi\\Lists\\Auth\\AdminLists"
        //}
    }


    public function isNotNeedLogin(): bool
    {
        $notNeedLogin = $this->notNeedLogin;
        if (empty($notNeedLogin)) {
            return false;
        }
        $action = request()->route()->getActionMethod();
        if (!in_array(trim($action), $notNeedLogin)) {
            return false;
        }
        return true;
    }


}
