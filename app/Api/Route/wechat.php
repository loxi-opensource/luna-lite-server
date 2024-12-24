<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Api\Controller\WechatController::class)->group(function () {
    Route::get('/wechat/jsConfig', 'jsConfig');
});
