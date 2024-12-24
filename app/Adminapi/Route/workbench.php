<?php

use App\Adminapi\Controller\WorkbenchController;
use Illuminate\Support\Facades\Route;

Route::controller(WorkbenchController::class)->group(function () {
    Route::get('/workbench/index', 'index');
});
