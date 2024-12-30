<?php

use App\Api\Controller\SwapTemplateController;
use Illuminate\Support\Facades\Route;

Route::controller(SwapTemplateController::class)->group(function () {
    Route::get('/swap_template/group_list', 'groupList');
});
