<?php


use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;

Route::group(['prefix' => 'tags'], function () {
    Route::get('/', [TagController::class, 'index']);
    Route::put('/{id}', [TagController::class, 'update']);
});

Route::group(['prefix' => 'posts'], function () {
    Route::get('/', [PostController::class, 'index']);
    Route::post('/{id}', [PostController::class, 'update']);
    Route::post('/{id}/delete', [PostController::class, 'destroy']);
});

Route::group(['prefix' => 'users'], function() {
    Route::get('/fake-users', [UserController::class, 'fakeUsers']);
});