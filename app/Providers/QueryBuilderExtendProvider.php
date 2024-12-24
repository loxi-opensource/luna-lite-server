<?php

namespace App\Providers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;

/**
 * 可以在此处扩展查询构建器的方法
 * applySearchWhere 用于应用搜索条件；
 *  兼容ThinkPHP的Where查询入参
 *  用法：$query->applySearchWhere([['name', 'like', '张三'], ['id', 'in', [1, 2, 3]]]);
 * applySortOrder 用于应用排序条件：
 *  用法：$query->applySortOrder(['id' => 'desc']);
 */
class QueryBuilderExtendProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        Builder::macro('applySearchWhere', function ($searchWhere) {
            foreach ($searchWhere as $where) {
                if ($where[1] == 'in') {
                    $this->whereIn($where[0], $where[2]);
                } else {
                    $this->where(...$where);
                }
            }
            return $this;
        });
        Builder::macro('applySortOrder', function ($sortOrder) {
            foreach ($sortOrder as $key => $value) {
                $this->orderBy($key, $value);
            }
            return $this;
        });
    }
}
