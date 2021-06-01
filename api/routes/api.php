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


Route::post('register', [AuthController::class, 'register']);
Route::post('password-forgot', [AuthController::class, 'passwordForgot']);
Route::post('password-reset', [AuthController::class, 'passwordReset']);

Route::middleware('auth:api')->namespace('Auth')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
});
