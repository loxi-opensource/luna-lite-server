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

use App\Adminapi\Controller\SwapRecordController;
use Illuminate\Support\Facades\Route;

/**
 * @notes 定义路由
 * @author likeadmin
 * @date 2024/12/30 15:36
 */
Route::controller(SwapRecordController::class)->group(function () {
    Route::get('/swap_record/lists', 'lists');
    Route::post('/swap_record/add', 'add');
    Route::post('/swap_record/edit', 'edit');
    Route::post('/swap_record/delete', 'delete');
    Route::get('/swap_record/detail', 'detail');
});
