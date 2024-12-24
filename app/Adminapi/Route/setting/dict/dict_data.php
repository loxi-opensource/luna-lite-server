<?php

use App\Adminapi\Controller\Setting\Dict\DictDataController;
use Illuminate\Support\Facades\Route;

Route::controller(DictDataController::class)->group(function () {
    Route::get('setting.dict.dict_data/lists', 'lists');
    Route::post('setting.dict.dict_data/add', 'add');
    Route::post('setting.dict.dict_data/edit', 'edit');
    Route::post('setting.dict.dict_data/delete', 'delete');
    Route::get('setting.dict.dict_data/detail', 'detail');
});
