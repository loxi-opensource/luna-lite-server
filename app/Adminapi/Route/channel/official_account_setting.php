<?php

use App\Adminapi\Controller\Channel\OfficialAccountSettingController;
use Illuminate\Support\Facades\Route;

Route::controller(OfficialAccountSettingController::class)->group(function () {
    Route::get('/channel.official_account_setting/getConfig', 'getConfig');
    Route::post('/channel.official_account_setting/setConfig', 'setConfig');
});
