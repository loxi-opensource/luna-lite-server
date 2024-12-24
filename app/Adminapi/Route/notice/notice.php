<?php

use App\Adminapi\Controller\Notice\NoticeController;
use Illuminate\Support\Facades\Route;

Route::controller(NoticeController::class)->group(function () {
    Route::get('/notice.notice/settingLists', 'settingLists');
    Route::get('/notice.notice/detail', 'detail');
    Route::post('/notice.notice/set', 'set');
});
