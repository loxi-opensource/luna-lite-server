<?php

namespace App\Common\Cache;

use Illuminate\Support\Facades\Cache;

/**
 * 缓存基础类，用于管理缓存
 */
abstract class BaseCache extends Cache
{
    /**
     * 缓存标签
     * @var string
     */
    protected $tagName;

    public function __construct()
    {
        $this->tagName = get_class($this);
    }

    public function set($key, $value, $ttl = null): bool
    {
        return self::store()->tags($this->tagName)->set($key, $value, $ttl);
    }

    public function get($key, $default = null)
    {
        return self::store()->tags($this->tagName)->get($key, $default);
    }

    public function delete($key): bool
    {
        return self::store()->tags($this->tagName)->delete($key);
    }

    public function inc($key, $value = 1): int
    {
        return self::store()->tags($this->tagName)->increment($key, $value);
    }

    public function deleteTag(): bool
    {
        return self::store()->tags($this->tagName)->flush();
    }

}
