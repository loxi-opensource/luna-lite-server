<?php

use App\Api\Controller\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function () {
    Route::get('/user/center', 'center');
    Route::get('/user/info', 'info');
    Route::post('/user/resetPassword', 'resetPassword');
    Route::post('/user/changePassword', 'changePassword');
    Route::post('/user/bindMobile', 'bindMobile');
    Route::post('/user/getMobileByMnp', 'getMobileByMnp');
    Route::post('/user/setInfo', 'setInfo');
});
