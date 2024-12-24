<?php

use App\Api\Controller\SearchController;
use Illuminate\Support\Facades\Route;

Route::controller(SearchController::class)->group(function () {
    Route::get('/search/hotLists', 'hotLists');
});
