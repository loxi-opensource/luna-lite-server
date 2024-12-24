<?php

namespace App\Api\Service;

use App\Common\Enum\User\UserTerminalEnum;
use App\Common\Enum\YesNoEnum;
use App\Common\Model\User\User;
use App\Common\Model\User\UserAuth;
use App\Common\Service\ConfigService;
use App\Common\Service\Storage\Driver as StorageDriver;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * 用户功能类（主要微信登录后创建和更新用户）
 */
class WechatUserService
{

    protected int $terminal = UserTerminalEnum::WECHAT_MMP;
    protected array $response = [];
    protected ?string $code = null;
    protected ?string $openid = null;
    protected ?string $unionid = null;
    protected ?string $nickname = null;
    protected ?string $headimgurl = null;
    protected User $user;


    public function __construct(array $response, int $terminal)
    {
        $this->terminal = $terminal;
        $this->setParams($response);
    }

    /**
     * @notes 设置微信返回的用户信息
     * @param $response
     */
    private function setParams($response): void
    {
        $this->response = $response;
        $this->openid = $response['openid'];
        $this->unionid = $response['unionid'] ?? '';
        $this->nickname = $response['nickname'] ?? '';
        $this->headimgurl = $response['headimgurl'] ?? '';
    }


    /**
     * @notes 根据opendid或unionid获取系统用户信息
     */
    public function getResopnseByUserInfo(): self
    {
        $openid = $this->openid;
        $unionid = $this->unionid;

        $user = DB::table('user as u')
            ->select('u.id', 'u.sn', 'u.mobile', 'u.nickname', 'u.avatar', 'u.mobile', 'u.is_disable', 'u.is_new_user')
            ->join('user_auth as au', 'au.user_id', '=', 'u.id')
            ->where(function ($query) use ($openid, $unionid) {
                $query->where('au.openid', $openid);
                if (isset($unionid) && $unionid) {
                    $query->orWhere('au.unionid', $unionid);
                }
            })
            ->first();

        if (!$user) {
            $user = new User();
        } else {
            $user = User::find($user->id);
        }

        $this->user = $user;
        return $this;
    }


    /**
     * @notes 获取用户信息
     * @param bool $isCheck 是否验证账号是否可用
     * @return array
     */
    public function getUserInfo($isCheck = true): array
    {
        if ($this->user->exists && $isCheck) {
            $this->checkAccount();
        }
        if ($this->user->exists) {
            $this->getToken();
        }
        return $this->user->toArray();
    }


    /**
     * @notes 校验账号
     */
    private function checkAccount()
    {
        if ($this->user->is_disable) {
            throw new Exception('您的账号异常，请联系客服。');
        }
    }


    /**
     * @notes 创建用户
     */
    private function createUser(): void
    {
        //设置头像
        if (empty($this->headimgurl)) {
            // 默认头像
            $defaultAvatar = config('project.default_image.user_avatar');
            $avatar = ConfigService::get('default_image', 'user_avatar', $defaultAvatar);
        } else {
            // 微信获取到的头像信息
            $avatar = $this->getAvatarByWechat();
        }

        $userSn = User::createUserSn();
        $this->user->sn = $userSn;
        $this->user->account = 'u' . $userSn;
        $this->user->nickname = "用户" . $userSn;
        $this->user->avatar = $avatar;
        $this->user->channel = $this->terminal;
        $this->user->is_new_user = YesNoEnum::YES;

        if ($this->terminal != UserTerminalEnum::WECHAT_MMP && !empty($this->nickname)) {
            $this->user->nickname = $this->nickname;
        }

        $this->user->save();

        UserAuth::create([
            'user_id' => $this->user->id,
            'openid' => $this->openid,
            'unionid' => $this->unionid,
            'terminal' => $this->terminal,
        ]);
    }


    /**
     * @notes 更新用户信息
     * @remark 该端没授权信息,重新写入一条该端的授权信息
     */
    private function updateUser(): void
    {
        // 无头像需要更新头像
        if (empty($this->user->avatar)) {
            $this->user->avatar = $this->getAvatarByWechat();
            $this->user->save();
        }

        $userAuth = UserAuth::query()->where(['user_id' => $this->user->id, 'openid' => $this->openid])
            ->firstOrNew();

        // 无该端授权信息，新增一条
        if (!$userAuth->exists) {
            $userAuth->user_id = $this->user->id;
            $userAuth->openid = $this->openid;
            $userAuth->unionid = $this->unionid;
            $userAuth->terminal = $this->terminal;
            $userAuth->save();
        } else {
            if (empty($userAuth['unionid']) && !empty($this->unionid)) {
                $userAuth->unionid = $this->unionid;
                $userAuth->save();
            }
        }
    }


    /**
     * @notes 获取token
     */
    private function getToken(): void
    {
        $user = UserTokenService::setToken($this->user->id, $this->terminal);
        $this->user->token = $user['token'];
    }


    /**
     * @notes 用户授权登录，
     * 如果用户不存在，创建用户；用户存在，更新用户信息，并检查该端信息是否需要写入
     */
    public function authUserLogin(): self
    {
        if (!$this->user->exists) {
            $this->createUser();
        } else {
            $this->updateUser();
        }
        return $this;
    }


    /**
     * @notes 处理从微信获取到的头像信息
     * @return string
     */
    public function getAvatarByWechat(): string
    {
        // 存储引擎
        $config = [
            'default' => ConfigService::get('storage', 'default', 'local'),
            'engine' => ConfigService::get('storage')
        ];

        $fileName = md5($this->openid . time()) . '.jpeg';

        if ($config['default'] == 'local') {
            // 本地存储
            $avatar = download_file($this->headimgurl, 'uploads/user/avatar/', $fileName);
        } else {
            // 第三方存储
            $avatar = 'uploads/user/avatar/' . $fileName;
            $storage = new StorageDriver($config);
            if (!$storage->fetch($this->headimgurl, $avatar)) {
                throw new Exception('头像保存失败:' . $storage->getError());
            }
        }
        return $avatar;
    }


}
