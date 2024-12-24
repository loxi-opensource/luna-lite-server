<?php

use App\Adminapi\Controller\Auth\RoleController;
use Illuminate\Support\Facades\Route;

Route::controller(RoleController::class)->group(function () {
    Route::get('/auth.role/all', 'all');
    Route::get('/auth.role/lists', 'lists');
    Route::post('/auth.role/add', 'add');
    Route::post('/auth.role/edit', 'edit');
    Route::post('/auth.role/delete', 'delete');
    Route::get('/auth.role/detail', 'detail');
});
