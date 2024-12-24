<?php

use App\Adminapi\Controller\Setting\Web\WebSettingController;
use Illuminate\Support\Facades\Route;

Route::controller(WebSettingController::class)->group(function () {
    Route::get('/setting.web.web_setting/getWebsite', 'getWebsite');
    Route::post('/setting.web.web_setting/setWebsite', 'setWebsite');
    Route::get('/setting.web.web_setting/getCopyright', 'getCopyright');
    Route::post('/setting.web.web_setting/setCopyright', 'setCopyright');
    Route::get('/setting.web.web_setting/getAgreement', 'getAgreement');
    Route::post('/setting.web.web_setting/setAgreement', 'setAgreement');
    Route::get('/setting.web.web_setting/getSiteStatistics', 'getSiteStatistics');
    Route::post('/setting.web.web_setting/setSiteStatistics', 'setSiteStatistics');
});
