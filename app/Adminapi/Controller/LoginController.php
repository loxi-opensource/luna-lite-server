<?php

namespace App\Adminapi\Controller;

use App\Adminapi\Logic\LoginLogic;
use App\Adminapi\Validate\LoginValidate;
use Illuminate\Http\Request;

/**
 * 管理员登录控制器
 * Class LoginController
 */
class LoginController extends BaseAdminController
{
    public array $notNeedLogin = ['account'];

    /**
     * @notes 账号登录
     */
    public function account(LoginValidate $validator)
    {
        $params = $validator->goCheck('account');
        return $this->data((new LoginLogic())->login($params));
    }

    /**
     * @notes 退出登录
     */
    public function logout(Request $request)
    {
        //退出登录情况特殊，只有成功的情况，也不需要token验证
        (new LoginLogic())->logout($request->attributes->get('adminInfo'));
        return $this->success();
    }
}
