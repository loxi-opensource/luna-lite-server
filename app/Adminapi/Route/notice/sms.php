<?php

use App\Adminapi\Controller\Notice\SmsConfigController;
use Illuminate\Support\Facades\Route;

Route::controller(SmsConfigController::class)->group(function () {
    Route::get('/notice.sms_config/getConfig', 'getConfig');
    Route::post('/notice.sms_config/setConfig', 'setConfig');
    Route::get('/notice.sms_config/detail', 'detail');
});
