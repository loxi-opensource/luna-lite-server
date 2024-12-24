<?php

use App\Adminapi\Controller\Setting\StorageController;
use Illuminate\Support\Facades\Route;

Route::controller(StorageController::class)->group(function () {
    Route::get('/setting.storage/lists', 'lists');
    Route::get('/setting.storage/detail', 'detail');
    Route::post('/setting.storage/setup', 'setup');
    Route::post('/setting.storage/change', 'change');
});
