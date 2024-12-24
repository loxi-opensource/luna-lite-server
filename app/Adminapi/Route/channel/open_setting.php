<?php

use App\Adminapi\Controller\Channel\OpenSettingController;
use Illuminate\Support\Facades\Route;

Route::controller(OpenSettingController::class)->group(function () {
    Route::get('/channel.open_setting/getConfig', 'getConfig');
    Route::post('/channel.open_setting/setConfig', 'setConfig');
});
