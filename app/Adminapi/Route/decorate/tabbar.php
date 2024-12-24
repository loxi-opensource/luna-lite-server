<?php

use App\Adminapi\Controller\Decorate\TabbarController;
use Illuminate\Support\Facades\Route;

Route::controller(TabbarController::class)->group(function () {
    Route::get('/decorate.tabbar/detail', 'detail');
    Route::post('/decorate.tabbar/save', 'save');
});
