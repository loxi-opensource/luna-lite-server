<?php

use App\Adminapi\Controller\Setting\Pay\PayConfigController;
use Illuminate\Support\Facades\Route;

Route::controller(PayConfigController::class)->group(function () {
    Route::get('/setting.pay.pay_config/getConfig', 'getConfig');
    Route::post('/setting.pay.pay_config/setConfig', 'setConfig');
    Route::get('/setting.pay.pay_config/lists', 'lists');
});
