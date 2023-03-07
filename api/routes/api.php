<?php
sleep(1);
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
use App\Http\Controllers\Content\CommentController;
use App\Http\Controllers\Content\PostController;
use App\Http\Controllers\Content\SearchController;
use App\Http\Controllers\Content\TagController;
use App\Http\Controllers\Rating\RatingController;

Route::post('register', [AuthController::class, 'register']);
Route::post('password-forgot', [AuthController::class, 'passwordForgot']);
Route::post('password-reset', [AuthController::class, 'passwordReset']);
Route::post('verify-email', [AuthController::class, 'verifyEmail']);

Route::middleware('auth:api')->namespace('Auth')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::group(['prefix' => 'ratings'], function() {
    Route::post('vote', [RatingController::class, 'vote'])
        ->middleware('auth:api');
});

Route::group(['prefix' => 'tags'], function () {
    Route::get('/search', [TagController::class, 'search']);
});

Route::group(['prefix' => 'content', 'as' => '.content'], function () {
    Route::group(['prefix' => 'posts'], function () {
        Route::get('/', [PostController::class, 'index']);
        Route::get('/{id}', [PostController::class, 'post']);
        Route::post('/increment-views/{id}', [PostController::class, 'incrementViews']);
        Route::post('/', [PostController::class, 'store'])->middleware([
            'auth:api',
            'image-sanitize'
        ]);
        Route::post('/{id}', [PostController::class, 'update'])->middleware([
            'auth:api',
            'image-sanitize'
        ]);
    });

    Route::group(['prefix' => 'comments', 'as' => '.comments'], function() {
        Route::get('/', [CommentController::class, 'comments']);
        Route::post('/', [CommentController::class, 'store'])
            ->name('.create');
        Route::post('/{id}', [CommentController::class, 'update'])
            ->name('.update');
    });

    Route::get('/search', [SearchController::class, 'index']);
});
