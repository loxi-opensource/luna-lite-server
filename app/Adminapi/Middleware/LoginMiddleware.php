<?php

namespace App\Adminapi\Middleware;

use App\Adminapi\Service\AdminTokenService;
use App\Common\Cache\AdminTokenCache;
use App\Common\Service\JsonService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class LoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('token');
        //判断接口是否免登录
        $isNotNeedLogin = $request->attributes->get('controllerObject')->isNotNeedLogin();

        //不直接判断$isNotNeedLogin结果，使不需要登录的接口通过，为了兼容某些接口可以登录或不登录访问
        if (empty($token) && !$isNotNeedLogin) {
            //没有token并且该地址需要登录才能访问
            return JsonService::fail('请求参数缺token', [], 0, 0);
        }

        $adminInfo = (new AdminTokenCache())->getAdminInfo($token);
        if (empty($adminInfo) && !$isNotNeedLogin) {
            //token过期无效并且该地址需要登录才能访问
            return JsonService::fail('登录超时，请重新登录', [], -1);
        }

        //token临近过期，自动续期
        if ($adminInfo) {
            //获取临近过期自动续期时长
            $beExpireDuration = Config::get('project.admin_token.be_expire_duration');
            //token续期
            if (time() > ($adminInfo['expire_time'] - $beExpireDuration)) {
                $result = AdminTokenService::overtimeToken($token);
                //续期失败（数据表被删除导致）
                if (empty($result)) {
                    return JsonService::fail('登录过期', [], -1);
                }
            }
        }

        //给request赋值，用于控制器
        $request->attributes->set('adminInfo', $adminInfo);
        $request->attributes->set('adminId', $adminInfo['admin_id'] ?? 0);

        return $next($request);
    }

}
