<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Content\PostController;

Route::post('register', [AuthController::class, 'register']);
Route::post('password-forgot', [AuthController::class, 'passwordForgot']);
Route::post('password-reset', [AuthController::class, 'passwordReset']);

Route::middleware('auth:api')->namespace('Auth')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::group(['prefix' => 'content'], function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{id}', [PostController::class, 'post']);
    Route::post('/posts', [PostController::class, 'store'])->middleware([
        'auth:api',
        'image-sanitize'
    ]);
    Route::put('/posts/{id}', [PostController::class, 'update'])->middleware([
        'auth:api',
        'image-sanitize'
    ]);
});
