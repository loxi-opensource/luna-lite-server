<?php

use App\Api\Controller\PayController;
use Illuminate\Support\Facades\Route;

Route::controller(PayController::class)->group(function () {
    Route::get('/pay/payWay', 'payWay');
    Route::post('/pay/prepay', 'prepay');
    Route::get('/pay/payStatus', 'payStatus');
    Route::post('/pay/notifyMnp', 'notifyMnp')->name('pay.notifyMnp');
    Route::post('/pay/notifyOa', 'notifyOa')->name('pay.notifyOa');
    Route::post('/pay/aliNotify', 'aliNotify')->name('pay.aliNotify');
});
