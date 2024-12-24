<?php

use App\Adminapi\Controller\Recharge\RechargeController;
use Illuminate\Support\Facades\Route;

Route::controller(RechargeController::class)->group(function () {
    Route::get('/recharge.recharge/getConfig', 'getConfig');
    Route::post('/recharge.recharge/setConfig', 'setConfig');
    Route::get('/recharge.recharge/lists', 'lists');
    Route::post('/recharge.recharge/refund', 'refund');
});
