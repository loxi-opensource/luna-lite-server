<?php

use App\Adminapi\Controller\Channel\OfficialAccountReplyController;
use Illuminate\Support\Facades\Route;

Route::controller(OfficialAccountReplyController::class)->group(function () {
    Route::get('/channel.official_account_reply/lists', 'lists');
    Route::post('/channel.official_account_reply/add', 'add');
    Route::get('/channel.official_account_reply/detail', 'detail');
    Route::post('/channel.official_account_reply/edit', 'edit');
    Route::post('/channel.official_account_reply/delete', 'delete');
    Route::post('/channel.official_account_reply/sort', 'sort');
    Route::post('/channel.official_account_reply/status', 'status');
    Route::any('/channel.official_account_reply/index', 'index')->name('channel.official_account_reply.index');
});
