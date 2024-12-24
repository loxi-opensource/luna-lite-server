<?php

use App\Adminapi\Controller\Channel\WebPageSettingController;
use Illuminate\Support\Facades\Route;

Route::controller(WebPageSettingController::class)->group(function () {
    Route::get('/channel.web_page_setting/getConfig', 'getConfig');
    Route::post('/channel.web_page_setting/setConfig', 'setConfig');
});
