<?php

use App\Api\Controller\SmsController;
use Illuminate\Support\Facades\Route;

Route::controller(SmsController::class)->group(function () {
    Route::post('/sms/sendCode', 'sendCode');
});
