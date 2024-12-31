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

use App\Adminapi\Controller\SwapTemplateGroupController;
use Illuminate\Support\Facades\Route;

/**
 * @notes 定义路由
 * @author likeadmin
 * @date 2024/12/30 14:44
 */
Route::controller(SwapTemplateGroupController::class)->group(function () {
    Route::get('/swap_template_group/lists', 'lists');
    Route::post('/swap_template_group/add', 'add');
    Route::post('/swap_template_group/edit', 'edit');
    Route::post('/swap_template_group/delete', 'delete');
    Route::get('/swap_template_group/detail', 'detail');
    Route::get('/swap_template_group/all', 'all');
});
