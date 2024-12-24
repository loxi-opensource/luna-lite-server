<?php

use App\Api\Controller\LoginController;
use Illuminate\Support\Facades\Route;

Route::controller(LoginController::class)->group(function () {
    Route::post('/login/account', 'account');
    Route::post('/login/register', 'register');
    Route::post('/login/logout', 'logout');
    Route::get('/login/codeUrl', 'codeUrl');
    Route::post('/login/oaLogin', 'oaLogin');
    Route::post('/login/mnpLogin', 'mnpLogin');
    Route::get('/login/getScanCode', 'getScanCode');
    Route::post('/login/scanLogin', 'scanLogin');
    Route::post('/login/wechatAuth', 'wechatAuth');
    Route::post('/login/updateUser', 'updateUser');
    Route::post('/login/mnpAuthBind', 'mnpAuthBind');
    Route::post('/login/oaAuthBind', 'oaAuthBind');
});
