<?php

namespace App\Adminapi\Middleware;

use App\Common\Cache\AdminAuthCache;
use App\Common\Service\JsonService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthMiddleware
{
    /**
     * 处理请求并执行权限验证
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->attributes->get('controllerObject')->isNotNeedLogin()) {
            return $next($request);
        }

        $adminInfo = $request->attributes->get('adminInfo');
        // 检查IP地址
        if ($adminInfo['login_ip'] !== $request->ip()) {
            return JsonService::fail('IP 地址发生变化，请重新登录', [], -1);
        }

        // 系统默认超级管理员，无需权限验证
        if ($adminInfo['root'] === 1) {
            return $next($request);
        }

        $adminAuthCache = new AdminAuthCache($adminInfo['admin_id']);

        // 获取请求 URI
        $requestUri = $request->getRequestUri();
        // 去掉查询字符串部分
        $baseUri = Str::before($requestUri, '?');
        // 去掉 '/adminapi/' 前缀
        $accessUri = Str::replaceFirst('/adminapi/', '', $baseUri);
        // 全部路由
        $allUri = $this->formatUrl($adminAuthCache->getAllUri());

        // 判断该当前访问的 URI 是否存在
        if (!in_array($accessUri, $allUri)) {
            return $next($request);
        }

        // 当前管理员拥有的路由权限
        $adminUris = $adminAuthCache->getAdminUri() ?? [];
        $adminUris = $this->formatUrl($adminUris);

        if (in_array($accessUri, $adminUris)) {
            return $next($request);
        }

        return JsonService::fail('权限不足，无法访问或操作');
    }

    /**
     * 格式化 URL
     *
     * @param array $data
     * @return array
     */
    protected function formatUrl(array $data)
    {
        return array_map(function ($item) {
            return strtolower(Str::camel($item));
        }, $data);
    }
}
