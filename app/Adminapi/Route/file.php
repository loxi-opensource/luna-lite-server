<?php

use App\Adminapi\Controller\FileController;
use Illuminate\Support\Facades\Route;

Route::controller(FileController::class)->group(function () {
    Route::get('/file/lists', 'lists');
    Route::post('/file/move', 'move');
    Route::post('/file/rename', 'rename');
    Route::post('/file/delete', 'delete');
    Route::get('/file/listCate', 'listCate');
    Route::post('/file/addCate', 'addCate');
    Route::post('/file/editCate', 'editCate');
    Route::post('/file/delCate', 'delCate');
});
