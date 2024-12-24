<?php

use App\Adminapi\Controller\UploadController;
use Illuminate\Support\Facades\Route;

Route::controller(UploadController::class)->group(function () {
    Route::post('/upload/image', 'image');
    Route::post('/upload/video', 'video');
    Route::post('/upload/file', 'file');
});
