<?php

use App\Adminapi\Controller\Dept\DeptController;
use Illuminate\Support\Facades\Route;

Route::controller(DeptController::class)->group(function () {
    Route::get('/dept.dept/lists', 'lists');
    Route::get('/dept.dept/leaderDept', 'leaderDept');
    Route::post('/dept.dept/add', 'add');
    Route::post('/dept.dept/edit', 'edit');
    Route::post('/dept.dept/delete', 'delete');
    Route::get('/dept.dept/detail', 'detail');
    Route::get('/dept.dept/all', 'all');
});
