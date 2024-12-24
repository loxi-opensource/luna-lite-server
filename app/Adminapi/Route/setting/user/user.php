<?php

use App\Adminapi\Controller\Setting\User\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function () {
    Route::get('/setting.user.user/getConfig', 'getConfig');
    Route::post('/setting.user.user/setConfig', 'setConfig');
    Route::get('/setting.user.user/getRegisterConfig', 'getRegisterConfig');
    Route::post('/setting.user.user/setRegisterConfig', 'setRegisterConfig');
});
