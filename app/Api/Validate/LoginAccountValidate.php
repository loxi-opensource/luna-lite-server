<?php

namespace App\Api\Validate;

use App\Common\Cache\UserAccountSafeCache;
use App\Common\Enum\LoginEnum;
use App\Common\Enum\Notice\NoticeEnum;
use App\Common\Enum\User\UserTerminalEnum;
use App\Common\Model\User\User;
use App\Common\Service\ConfigService;
use App\Common\Service\Sms\SmsDriver;
use App\Common\Validate\BaseValidate;
use Closure;


/**
 * 账号密码登录校验
 * Class LoginAccountValidate
 * @package App\Api\Validate
 */
class LoginAccountValidate extends BaseValidate
{
    protected $messages = [
        'terminal.required' => '终端参数缺失',
        'terminal.in' => '终端参数状态值不正确',
        'scene.required' => '场景不能为空',
        'scene.in' => '场景值错误',
        'account.required' => '请输入账号',
        'password.required' => '请输入密码',
    ];

    public function rules($scene = 'default')
    {
        return [
            'terminal' => 'required|in:' . implode(',', [
                    UserTerminalEnum::WECHAT_MMP,
                    UserTerminalEnum::WECHAT_OA,
                    UserTerminalEnum::H5,
                    UserTerminalEnum::PC,
                    UserTerminalEnum::IOS,
                    UserTerminalEnum::ANDROID,
                ]),
            'scene' => [
                'required',
                'in:' . implode(',', [
                    LoginEnum::ACCOUNT_PASSWORD,
                    LoginEnum::MOBILE_CAPTCHA,
                ]),
                function ($attribute, $value, Closure $fail) {
                    $this->checkConfig($value, $fail);
                },
            ],
            'account' => 'required',
            'password' => 'required_if:scene,' . LoginEnum::ACCOUNT_PASSWORD,
            'code' => 'required_if:scene,' . LoginEnum::MOBILE_CAPTCHA,
        ];
    }

    public function validate($data)
    {
        $validator = Validator::make($data, $this->rules(), $this->messages);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return true;
    }

    protected function checkConfig($scene, Closure $fail)
    {
        $config = ConfigService::get('login', 'login_way');
        if (!in_array($scene, $config)) {
            return $fail('不支持的登录方式');
            return;
        }

        // 账号密码登录
        $data = request()->all();
        if ($scene == LoginEnum::ACCOUNT_PASSWORD) {
            if (!isset($data['password'])) {
                return $fail('请输入密码');
            } else {
                $passwordCheck = $this->checkPassword($data['password'], $data);
                if ($passwordCheck !== true) {
                    return $fail($passwordCheck);
                }
            }
        }

        // 手机验证码登录
        if ($scene == LoginEnum::MOBILE_CAPTCHA) {
            if (!isset($data['code'])) {
                return $fail('请输入手机验证码');
            } else {
                $codeCheck = $this->checkCode($data['code'], $data);
                if ($codeCheck !== true) {
                    return $fail($codeCheck);
                }
            }
        }
    }

    protected function checkPassword($password, $data)
    {
        // 账号安全机制，连续输错后锁定，防止账号密码暴力破解
        $userAccountSafeCache = new UserAccountSafeCache();
        if (!$userAccountSafeCache->isSafe()) {
            return '密码连续' . $userAccountSafeCache->count . '次输入错误，请' . $userAccountSafeCache->minute . '分钟后重试';
        }

        $query = User::query()
            ->where('account', $data['account'])
            ->orWhere('mobile', $data['account']);
        $userInfo = $query->select(['password', 'is_disable'])->first();

        if (!$userInfo) {
            return '用户不存在';
        }

        if ($userInfo->is_disable) {
            return '用户已禁用';
        }

        if (empty($userInfo->password)) {
            $userAccountSafeCache->record();
            return '用户不存在';
        }

        $passwordSalt = config('project.unique_identification');
        if ($userInfo->password !== create_password($password, $passwordSalt)) {
            $userAccountSafeCache->record();
            return '密码错误';
        }

        $userAccountSafeCache->relieve();
        return true;
    }

    protected function checkCode($code, $data)
    {
        $smsDriver = new SmsDriver();
        $result = $smsDriver->verify($data['account'], $code, NoticeEnum::LOGIN_CAPTCHA);
        return $result ? true : '验证码错误';
    }
}
