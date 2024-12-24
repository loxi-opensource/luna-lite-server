<?php

use App\Adminapi\Controller\Article\ArticleController;
use Illuminate\Support\Facades\Route;

Route::controller(ArticleController::class)->group(function () {
    Route::get('/article.article/lists', 'lists');
    Route::post('/article.article/add', 'add');
    Route::post('/article.article/edit', 'edit');
    Route::post('/article.article/delete', 'delete');
    Route::get('/article.article/detail', 'detail');
    Route::post('/article.article/updateStatus', 'updateStatus');
});
