<?php

use App\Adminapi\Controller\Setting\Dict\DictTypeController;
use Illuminate\Support\Facades\Route;

Route::controller(DictTypeController::class)->group(function () {
    Route::get('setting.dict.dict_type/lists', 'lists');
    Route::post('setting.dict.dict_type/add', 'add');
    Route::post('setting.dict.dict_type/edit', 'edit');
    Route::post('setting.dict.dict_type/delete', 'delete');
    Route::get('setting.dict.dict_type/detail', 'detail');
    Route::get('setting.dict.dict_type/all', 'all');
});
