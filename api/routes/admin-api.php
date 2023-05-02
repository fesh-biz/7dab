<?php


use App\Http\Controllers\Admin\TagController;

Route::group(['prefix' => 'tags'], function () {
    Route::get('paginated-search', [TagController::class, 'paginatedSearch']);
    Route::put('/{id}', [TagController::class, 'update']);
});