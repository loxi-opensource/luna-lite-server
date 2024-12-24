<?php

use App\Adminapi\Controller\Tools\GeneratorController;
use Illuminate\Support\Facades\Route;

Route::controller(GeneratorController::class)->group(function () {
    Route::get('/tools.generator/dataTable', 'dataTable');
    Route::get('/tools.generator/generateTable', 'generateTable');
    Route::post('/tools.generator/selectTable', 'selectTable');
    Route::post('/tools.generator/generate', 'generate');
    Route::get('/tools.generator/download', 'download');
    Route::post('/tools.generator/preview', 'preview');
    Route::post('/tools.generator/syncColumn', 'syncColumn');
    Route::post('/tools.generator/edit', 'edit');
    Route::get('/tools.generator/detail', 'detail');
    Route::post('/tools.generator/delete', 'delete');
    Route::get('/tools.generator/getModels', 'getModels');
});
