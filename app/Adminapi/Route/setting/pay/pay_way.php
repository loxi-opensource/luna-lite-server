<?php

use App\Adminapi\Controller\Setting\Pay\PayWayController;
use Illuminate\Support\Facades\Route;

Route::controller(PayWayController::class)->group(function () {
    Route::get('/setting.pay.pay_way/getPayWay', 'getPayWay');
    Route::post('/setting.pay.pay_way/setPayWay', 'setPayWay');
});
