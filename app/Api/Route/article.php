<?php

use App\Api\Controller\ArticleController;
use Illuminate\Support\Facades\Route;

Route::controller(ArticleController::class)->group(function () {
    Route::get('/article/lists', 'lists');
    Route::get('/article/cate', 'cate');
    Route::get('/article/detail', 'detail');
    Route::get('/article/collect', 'collect');
    Route::post('/article/addCollect', 'addCollect');
    Route::post('/article/cancelCollect', 'cancelCollect');
});
