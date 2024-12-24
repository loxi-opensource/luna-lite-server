<?php

namespace App\Common\Cache;


/**
 * //后台账号安全机制，连续输错后锁定，防止账号密码暴力破解
 */
class UserAccountSafeCache extends BaseCache
{

    private $key;//缓存次数名称
    public $minute = 15;//缓存设置为15分钟，即密码错误次数达到，锁定15分钟
    public $count = 15;  //设置连续输错次数，即15分钟内连续输错误15次后，锁定

    public function __construct()
    {
        parent::__construct();
        $ip = \request()->ip();
        $this->key = $this->tagName . $ip;
    }

    /**
     * @notes 记录登录错误次数
     */
    public function record()
    {
        if ($this->get($this->key)) {
            //缓存存在，记录错误次数
            $this->inc($this->key, 1);
        } else {
            //缓存不存在，第一次设置缓存
            $this->set($this->key, 1, $this->minute * 60);
        }
    }

    /**
     * @notes 判断是否安全
     * @return bool
     */
    public function isSafe()
    {
        $count = $this->get($this->key);
        if ($count >= $this->count) {
            return false;
        }
        return true;
    }

    /**
     * @notes 删除该ip记录错误次数
     */
    public function relieve()
    {
        $this->delete($this->key);
    }


}
