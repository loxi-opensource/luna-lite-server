<?php

use App\Api\Controller\UploadController;
use Illuminate\Support\Facades\Route;

Route::controller(UploadController::class)->group(function () {
    Route::post('/upload/image', 'image');
});
