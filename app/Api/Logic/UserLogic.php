<?php

namespace App\Api\Logic;

use App\Common\Enum\Notice\NoticeEnum;
use App\Common\Enum\User\UserTerminalEnum;
use App\Common\Enum\YesNoEnum;
use App\Common\Logic\BaseLogic;
use App\Common\Model\User\User;
use App\Common\Model\User\UserAuth;
use App\Common\Service\FileService;
use App\Common\Service\Sms\SmsDriver;
use App\Common\Service\Wechat\WechatMnpService;
use Illuminate\Support\Facades\Config;

/**
 * 会员逻辑层
 */
class UserLogic extends BaseLogic
{
    /**
     * @notes 个人中心
     * @param array $userInfo
     * @return array
     */
    public static function center(array $userInfo): array
    {
        $user = User::select('id', 'sn', 'sex', 'account', 'nickname', 'real_name', 'avatar', 'mobile', 'create_time', 'is_new_user', 'user_money', 'password')
            ->where('id', $userInfo['user_id'])
            ->first();

        if (in_array($userInfo['terminal'], [UserTerminalEnum::WECHAT_MMP, UserTerminalEnum::WECHAT_OA])) {
            $auth = UserAuth::where(['user_id' => $userInfo['user_id'], 'terminal' => $userInfo['terminal']])->first();
            $user->is_auth = $auth ? YesNoEnum::YES : YesNoEnum::NO;
        }

        $user->has_password = !empty($user->password);
        unset($user->password); // 隐藏密码
        return $user->toArray();
    }

    /**
     * @notes 个人信息
     * @param int $userId
     * @return array
     */
    public static function info(int $userId): array
    {
        $user = User::select('id', 'sn', 'sex', 'account', 'password', 'nickname', 'real_name', 'avatar', 'mobile', 'create_time', 'user_money')
            ->where('id', $userId)
            ->first();

        $user->has_password = !empty($user->password);
        $user->has_auth = self::hasWechatAuth($userId);
        $user->version = config('project.version');
        unset($user->password); // 隐藏密码

        return $user->toArray();
    }

    /**
     * @notes 设置用户信息
     * @param int $userId
     * @param array $params
     * @return User|false
     */
    public static function setInfo(int $userId, array $params)
    {
        try {
            if ($params['field'] == "avatar") {
                $params['value'] = FileService::setFileUrl($params['value']);
            }

            return User::where('id', $userId)->update([$params['field'] => $params['value']]);
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * @notes 是否有微信授权信息
     * @param int $userId
     * @return bool
     */
    public static function hasWechatAuth(int $userId): bool
    {
        $terminal = [UserTerminalEnum::WECHAT_MMP, UserTerminalEnum::WECHAT_OA, UserTerminalEnum::PC];
        $auth = UserAuth::where('user_id', $userId)
            ->whereIn('terminal', $terminal)
            ->first();

        return !is_null($auth);
    }

    /**
     * @notes 重置登录密码
     * @param array $params
     * @return bool
     */
    public static function resetPassword(array $params): bool
    {
        try {
            // 校验验证码
            $smsDriver = new SmsDriver();
            if (!$smsDriver->verify($params['mobile'], $params['code'], NoticeEnum::FIND_LOGIN_PASSWORD_CAPTCHA)) {
                throw new \Exception('验证码错误');
            }

            // 重置密码
            $passwordSalt = Config::get('project.unique_identification');
            $password = create_password($params['password'], $passwordSalt);

            // 更新
            User::where('mobile', $params['mobile'])->update(['password' => $password]);

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 修改密码
     * @param array $params
     * @param int $userId
     * @return bool
     */
    public static function changePassword(array $params, int $userId): bool
    {
        try {
            $user = User::findOrFail($userId);

            // 密码盐
            $passwordSalt = Config::get('project.unique_identification');

            if (!empty($user->password)) {
                if (empty($params['old_password'])) {
                    throw new \Exception('请填写旧密码');
                }
                $oldPassword = create_password($params['old_password'], $passwordSalt);
                if ($oldPassword !== $user->password) {
                    throw new \Exception('原密码不正确');
                }
            }

            // 保存新密码
            $password = create_password($params['password'], $passwordSalt);
            $user->password = $password;
            $user->save();

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 获取小程序手机号
     * @param array $params
     * @return bool
     */
    public static function getMobileByMnp(array $params): bool
    {
        try {
            $response = (new WechatMnpService())->getUserPhoneNumber($params['code']);
            $phoneNumber = $response['phone_info']['purePhoneNumber'] ?? '';
            if (empty($phoneNumber)) {
                throw new \Exception('获取手机号码失败');
            }

            $user = User::where('mobile', $phoneNumber)
                ->where('id', '<>', $params['user_id'])
                ->first();

            if (!is_null($user)) {
                throw new \Exception('手机号已被其他账号绑定');
            }

            // 绑定手机号
            User::where('id', $params['user_id'])->update(['mobile' => $phoneNumber]);

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 绑定手机号
     * @param array $params
     * @return bool
     */
    public static function bindMobile(array $params): bool
    {
        try {
            // 变更手机号场景
            $sceneId = NoticeEnum::CHANGE_MOBILE_CAPTCHA;
            $where = [
                ['id', '=', $params['user_id']],
                ['mobile', '=', $params['mobile']]
            ];

            // 绑定手机号场景
            if ($params['type'] == 'bind') {
                $sceneId = NoticeEnum::BIND_MOBILE_CAPTCHA;
                $where = [['mobile', '=', $params['mobile']]];
            }

            // 校验短信
            $checkSmsCode = (new SmsDriver())->verify($params['mobile'], $params['code'], $sceneId);
            if (!$checkSmsCode) {
                throw new \Exception('验证码错误');
            }

            $user = User::where($where)->first();
            if (!is_null($user)) {
                throw new \Exception('该手机号已被使用');
            }

            User::where('id', $params['user_id'])->update(['mobile' => $params['mobile']]);

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }
}
