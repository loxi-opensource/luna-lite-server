<?php

use App\Api\Controller\FaceSwapController;
use Illuminate\Support\Facades\Route;

Route::controller(FaceSwapController::class)->group(function () {
    Route::post('/face_swap/create_avatar', 'createAvatar');
    Route::post('/face_swap/create_swap', 'createSwap');
});
