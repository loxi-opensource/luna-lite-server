<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Api\Controller\RechargeController::class)->group(function () {
    Route::get('/recharge/lists', 'lists');
    Route::post('/recharge/recharge', 'recharge');
    Route::get('/recharge/config', 'config');
});
