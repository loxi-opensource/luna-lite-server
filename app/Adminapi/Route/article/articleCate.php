<?php

use App\Adminapi\Controller\Article\ArticleCateController;
use Illuminate\Support\Facades\Route;

Route::controller(ArticleCateController::class)->group(function () {
    Route::get('/article.articleCate/lists', 'lists');
    Route::post('/article.articleCate/add', 'add');
    Route::post('/article.articleCate/edit', 'edit');
    Route::post('/article.articleCate/delete', 'delete');
    Route::get('/article.articleCate/detail', 'detail');
    Route::post('/article.articleCate/updateStatus', 'updateStatus');
    Route::get('/article.articleCate/all', 'all');
});
