<?php

use App\Adminapi\Controller\Finance\RefundController;
use Illuminate\Support\Facades\Route;

Route::controller(RefundController::class)->group(function () {
    Route::get('/finance.refund/record', 'record');
    Route::get('/finance.refund/log', 'log');
    Route::get('/finance.refund/stat', 'stat');
});
