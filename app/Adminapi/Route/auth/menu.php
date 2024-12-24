<?php

use App\Adminapi\Controller\Auth\MenuController;
use Illuminate\Support\Facades\Route;

Route::controller(MenuController::class)->group(function () {
    Route::get('/auth.menu/all', 'all');
    Route::get('/auth.menu/lists', 'lists');
    Route::post('/auth.menu/add', 'add');
    Route::post('/auth.menu/edit', 'edit');
    Route::post('/auth.menu/delete', 'delete');
    Route::get('/auth.menu/detail', 'detail');
});
