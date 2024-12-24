<?php

use App\Adminapi\Controller\Channel\MnpSettingsController;
use Illuminate\Support\Facades\Route;

Route::controller(MnpSettingsController::class)->group(function () {
    Route::get('/channel.mnp_settings/getConfig', 'getConfig');
    Route::post('/channel.mnp_settings/setConfig', 'setConfig');
});
