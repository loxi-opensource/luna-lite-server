<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Api\Controller\AccountLogController::class)->group(function () {
    Route::get('/account_log/lists', 'lists');
});
