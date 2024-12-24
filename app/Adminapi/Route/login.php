<?php

use App\Adminapi\Controller\LoginController;
use Illuminate\Support\Facades\Route;

Route::controller(LoginController::class)->group(function () {
    Route::post('/login/account', 'account');
    Route::post('/login/logout', 'logout');
});
