<?php

use App\Api\Controller\PcController;
use Illuminate\Support\Facades\Route;

Route::controller(PcController::class)->group(function () {
    Route::get('/pc/index', 'index');
    Route::get('/pc/config', 'config');
    Route::get('/pc/infoCenter', 'infoCenter');
    Route::get('/pc/articleDetail', 'articleDetail');
});
