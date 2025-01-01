<?php

namespace App\Adminapi\Middleware;

use App\Common\Model\OperationLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OperationLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        /**
         * @var $controllerObject \App\Adminapi\Controller\BaseAdminController
         */
        $controllerObject = $request->attributes->get('controllerObject');

        // 需要登录的接口，无效访问时不记录
        $isNotNeedLogin = $controllerObject->isNotNeedLogin();
        if (!$isNotNeedLogin && empty($controllerObject->getAdminInfo())) {
            return $response;
        }

        //不记录日志操作
        if (!$controllerObject->shouldLogOperation) {
            return $response;
        }

        // 获取操作注解
        $notes = '';
        try {
            $re = new \ReflectionClass($controllerObject);
            $doc = $re->getMethod($request->route()->getActionMethod())->getDocComment();
            if (empty($doc)) {
                throw new \Exception('请给控制器方法注释');
            }
            preg_match('/\s(\w+)/u', $doc, $values);
            $notes = trim($values[0] ?? '');
        } catch (\Exception $e) {
            $notes = '无法获取操作名称，请给控制器方法注释';
        }

        $params = $request->all();

        // 过滤敏感参数
        $sensitiveParams = [
            'password', 'app_secret', 'password_old', 'password_confirm'
        ];
        foreach ($sensitiveParams as $sensitiveParam) {
            if (isset($params[$sensitiveParam])) {
                $params[$sensitiveParam] = "******";
            }
        }

        // 导出数据操作进行记录
        if (isset($params['export']) && $params['export'] == 2) {
            $notes .= '-数据导出';
        }

        // 记录日志
        $adminInfo = $controllerObject->getAdminInfo();
        $systemLog = new OperationLog();
        $systemLog->admin_id = $adminInfo['admin_id'] ?? 0;
        $systemLog->admin_name = $adminInfo['name'] ?? '';
        $systemLog->action = $notes;
        $systemLog->account = $adminInfo['account'] ?? '';
        $systemLog->url = $request->url();
        $systemLog->type = $request->method();
        $systemLog->params = mb_substr(json_encode($params, JSON_UNESCAPED_UNICODE), 0, 1000);
        $systemLog->ip = $request->ip();
        $systemLog->result = mb_substr($response->getContent(), 0, 1000);
        $systemLog->create_time = time();
        $systemLog->save();

        return $response;
    }

}
