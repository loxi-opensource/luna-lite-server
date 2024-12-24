<?php

use App\Adminapi\Middleware\AuthMiddleware;
use App\Adminapi\Middleware\InitMiddleware;
use App\Adminapi\Middleware\LoginMiddleware;
use App\Adminapi\Middleware\OperationLogMiddleware;
use App\Api\Middleware\InitMiddleware as ApiInitMiddleware;
use App\Api\Middleware\LoginMiddleware as ApiLoginMiddleware;
use App\Common\Service\JsonService;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            // 用户端API
            Route::prefix('api')
                ->middleware([
                    ApiInitMiddleware::class, // 初始化
                    ApiLoginMiddleware::class, // 登录验证
                ])
                ->group(app_path('Api/Route/index.php'));

            // 管理后台API
            Route::prefix('adminapi')
                ->middleware([
                    InitMiddleware::class, // 初始化
                    LoginMiddleware::class, // 登录验证
                    AuthMiddleware::class, // 权限认证
                    OperationLogMiddleware::class, // 操作日志
                ])
                ->group(app_path('Adminapi/Route/index.php'));

            // Laravel默认路由
            Route::prefix('/')
                ->group(base_path('routes/web.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            // 修改Laravel默认中间件
            \Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks::class,
//            \Illuminate\Http\Middleware\TrustHosts::class,
            \Illuminate\Http\Middleware\TrustProxies::class,
//            \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
            // 兼容TP框架：不需要转换空字符串为null
//            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ])->append([
            // Likeadmin跨域请求中间件
            \App\Middleware\LikeAdminAllowMiddleware::class,
            // 在这里添加其他自定义全局中间件
            // ...

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $e) {
            return JsonService::throw($e->validator->errors()->first());
        });
    })
    ->create();
