<?php

use App\Adminapi\Controller\User\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function () {
    Route::get('/user.user/lists', 'lists');
    Route::get('/user.user/detail', 'detail');
    Route::post('/user.user/edit', 'edit');
    Route::post('/user.user/adjustMoney', 'adjustMoney');
});
