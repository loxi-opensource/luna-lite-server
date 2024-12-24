<?php

use App\Adminapi\Controller\Setting\System\LogController;
use Illuminate\Support\Facades\Route;

Route::controller(LogController::class)->group(function () {
    Route::get('/setting.system.log/lists', 'lists');
});
