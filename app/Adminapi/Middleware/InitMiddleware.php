<?php

namespace App\Adminapi\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class InitMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $controllerClass = $request->route()->getControllerClass();
        // 创建控制器对象
        $controllerObject = App::make($controllerClass);
        // 将控制器对象存储到请求中
        $request->attributes->set('controllerObject', $controllerObject);
        return $next($request);
    }
}
