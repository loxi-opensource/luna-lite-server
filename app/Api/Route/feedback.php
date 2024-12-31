<?php

use App\Api\Controller\FeedbackController;
use Illuminate\Support\Facades\Route;

Route::controller(FeedbackController::class)->group(function () {
    Route::post('/feedback/add', 'add');
});
