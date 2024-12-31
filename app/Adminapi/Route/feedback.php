<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

use App\Adminapi\Controller\FeedbackController;
use Illuminate\Support\Facades\Route;

/**
 * @notes 定义用户反馈路由
 * @author likeadmin
 * @date 2024/12/31 15:27
 */
Route::controller(FeedbackController::class)->group(function () {
    Route::get('/feedback/lists', 'lists');
    Route::post('/feedback/add', 'add');
    Route::post('/feedback/edit', 'edit');
    Route::post('/feedback/delete', 'delete');
    Route::get('/feedback/detail', 'detail');
});
