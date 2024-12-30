<?php

use App\Api\Controller\FaceSwapController;
use Illuminate\Support\Facades\Route;

Route::controller(FaceSwapController::class)->group(function () {
    Route::post('/face_swap/upload_image', 'uploadImage');
});
