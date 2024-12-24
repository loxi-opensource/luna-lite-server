<?php

use App\Adminapi\Controller\DownloadController;
use Illuminate\Support\Facades\Route;

Route::controller(DownloadController::class)->group(function () {
    Route::get('/download/export', 'export');
});
