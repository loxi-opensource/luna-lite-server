<?php

use App\Adminapi\Controller\Setting\FaceSwap\FaceSwapSettingController;
use Illuminate\Support\Facades\Route;

Route::controller(FaceSwapSettingController::class)->group(function () {
    Route::get('/setting.face_swap.setting/getFaceSwapKey', 'getFaceSwapKey');
    Route::post('/setting.face_swap.setting/setFaceSwapKey', 'setFaceSwapKey');
});
