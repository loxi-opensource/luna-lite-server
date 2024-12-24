<?php

use App\Adminapi\Controller\Decorate\PageController;
use Illuminate\Support\Facades\Route;

Route::controller(PageController::class)->group(function () {
    Route::get('/decorate.page/detail', 'detail');
    Route::post('/decorate.page/save', 'save');
});
