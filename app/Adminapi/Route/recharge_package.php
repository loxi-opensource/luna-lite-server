<?php
// +----------------------------------------------------------------------
// | Luna-Swapping开源AI换脸项目Lite版本（基于likeadmin_laravel框架）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | Github开源仓库：https://github.com/loxi-opensource/luna-lite-server
// | 全栈开发框架：https://github.com/1nFrastr/likeadmin_laravel
// | 独立开发者博客：https://www.sodair.top
// +----------------------------------------------------------------------
// | author: 1nFrastr x likeadminTeam
// +----------------------------------------------------------------------

use App\Adminapi\Controller\RechargePackageController;
use Illuminate\Support\Facades\Route;

/**
 * @notes 定义路由
 * @author likeadmin
 * @date 2025/01/30 16:55
 */
Route::controller(RechargePackageController::class)->group(function () {
    Route::get('/recharge_package/lists', 'lists');
    Route::post('/recharge_package/add', 'add');
    Route::post('/recharge_package/edit', 'edit');
    Route::post('/recharge_package/delete', 'delete');
    Route::get('/recharge_package/detail', 'detail');
});
