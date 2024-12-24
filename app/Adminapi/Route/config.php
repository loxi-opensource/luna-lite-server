<?php

use App\Adminapi\Controller\ConfigController;
use Illuminate\Support\Facades\Route;

Route::controller(ConfigController::class)->group(function () {
    Route::get('/config/getConfig', 'getConfig');
    Route::get('/config/dict', 'dict');
});
