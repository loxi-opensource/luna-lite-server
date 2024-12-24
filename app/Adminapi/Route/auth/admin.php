<?php

use App\Adminapi\Controller\Auth\AdminController;
use Illuminate\Support\Facades\Route;

Route::controller(AdminController::class)->group(function () {
    Route::get('/auth.admin/mySelf', 'mySelf');
    Route::get('/auth.admin/lists', 'lists');
    Route::post('/auth.admin/add', 'add');
    Route::post('/auth.admin/edit', 'edit');
    Route::post('/auth.admin/delete', 'delete');
    Route::post('/auth.admin/editSelf', 'editSelf');
    Route::get('/auth.admin/detail', 'detail');
});
