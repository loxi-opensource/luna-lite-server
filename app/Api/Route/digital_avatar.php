<?php

use App\Api\Controller\DigitalAvatarController;
use Illuminate\Support\Facades\Route;

Route::controller(DigitalAvatarController::class)->group(function () {
    Route::get('/digital_avatar/list', 'list');
    Route::post('/digital_avatar/remove', 'remove');
});
