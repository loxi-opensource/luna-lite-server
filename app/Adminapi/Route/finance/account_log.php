<?php

use App\Adminapi\Controller\Finance\AccountLogController;
use Illuminate\Support\Facades\Route;

Route::controller(AccountLogController::class)->group(function () {
    Route::get('/finance.account_log/lists', 'lists');
    Route::get('/finance.account_log/getUmChangeType', 'getUmChangeType');
});
