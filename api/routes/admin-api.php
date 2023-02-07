<?php

use App\Http\Controllers\Content\TagController;

Route::group(['prefix' => 'tags'], function () {
    Route::get('paginated-search', [TagController::class, 'paginatedSearch']);
});