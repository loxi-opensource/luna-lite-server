<?php

use App\Adminapi\Controller\Decorate\DataController;
use Illuminate\Support\Facades\Route;

Route::controller(DataController::class)->group(function () {
    Route::get('/decorate.data/article', 'article');
    Route::get('/decorate.data/pc', 'pc');
});
