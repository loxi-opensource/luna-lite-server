<?php

use App\Adminapi\Controller\Channel\OfficialAccountMenuController;
use Illuminate\Support\Facades\Route;

Route::controller(OfficialAccountMenuController::class)->group(function () {
    Route::get('/channel.official_account_menu/detail', 'detail');
    Route::post('/channel.official_account_menu/save', 'save');
    Route::post('/channel.official_account_menu/saveAndPublish', 'saveAndPublish');

});
