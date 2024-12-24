<?php

namespace App\Common\Cache;

class WebScanLoginCache extends BaseCache
{
    private $prefix = 'web_scan_';

    /**
     * @notes 获取扫码登录状态标记
     */
    public function getScanLoginState($state)
    {
        return $this->get($this->prefix . $state);
    }

    /**
     * @notes 设置扫码登录状态
     */
    public function setScanLoginState($state)
    {
        $this->set($this->prefix . $state, $state, 600);
        return $this->getScanLoginState($state);
    }

    /**
     * @notes 删除缓存
     */
    public function deleteLoginState($state)
    {
        return $this->delete($this->prefix . $state);
    }
}
