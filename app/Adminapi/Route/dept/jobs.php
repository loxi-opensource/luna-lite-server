<?php

use App\Adminapi\Controller\Dept\JobsController;
use Illuminate\Support\Facades\Route;

Route::controller(JobsController::class)->group(function () {
    Route::get('/dept.jobs/lists', 'lists');
    Route::get('/dept.jobs/all', 'all');
    Route::get('/dept.jobs/detail', 'detail');
    Route::post('/dept.jobs/add', 'add');
    Route::post('/dept.jobs/edit', 'edit');
    Route::post('/dept.jobs/delete', 'delete');
});
