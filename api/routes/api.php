<?php
// sleep(1);
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
use App\Http\Controllers\Media\MediaController;
use App\Http\Controllers\Rating\RatingController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\UserController;

Route::post('register', [AuthController::class, 'register']);
Route::post('password-forgot', [AuthController::class, 'passwordForgot']);
Route::post('password-reset', [AuthController::class, 'passwordReset']);
Route::post('verify-email', [AuthController::class, 'verifyEmail']);

Route::middleware('auth:api')->namespace('Auth')->group(function () {
    Route::get('/me', [UserController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('/resend-email-verification', [AuthController::class, 'resendEmailVerification']);
});

// Media
Route::group(['prefix' => 'media', 'middleware' => 'auth:api'], function() {
    Route::post('/', [MediaController::class, 'create']);
    Route::post('/check-file', [MediaController::class, 'checkFile'])->middleware();
    Route::delete('/{id}', [MediaController::class, 'delete']);
});

// Users

Route::group(['prefix' => 'users'], function () {
    Route::get('{id}', [UserController::class, 'find']);
    Route::get('{id}/stats', [UserController::class, 'stats']);
    Route::get('{id}/posts', [UserController::class, 'posts']);
});

// Profile

Route::group(['prefix' => 'profile', 'middleware' => 'auth:api'], function () {
    Route::get('content-stats', [ProfileController::class, 'contentStats']);
    Route::get('posts', [ProfileController::class, 'posts']);
    Route::get('comments', [ProfileController::class, 'comments']);
    Route::get('answers', [ProfileController::class, 'answers']);
    Route::post('avatar', [ProfileController::class, 'uploadAvatar'])
        ->middleware('image-sanitize');
});

// Rating

Route::group(['prefix' => 'ratings'], function () {
    Route::post('vote', [RatingController::class, 'vote'])
        ->middleware('auth:api');
});

// Tags

Route::group(['prefix' => 'tags'], function () {
    Route::get('/search', [TagController::class, 'search']);
});

// Posts

Route::group(['prefix' => 'posts'], function () {
    Route::get('/top', [PostController::class, 'top']);
    Route::get('/{id}', [PostController::class, 'postView']);
    Route::get('/{id}/comments', [PostController::class, 'postComments']);
    Route::get('/{id}/preview', [PostController::class, 'postPreview'])
        ->middleware('auth:api');
    Route::post('/increment-views/{id}', [PostController::class, 'incrementPostViewsCounter']);
    Route::post('/', [PostController::class, 'store'])->middleware([
        'auth:api',
        'image-sanitize'
    ]);
    Route::post('/{id}', [PostController::class, 'update'])->middleware([
        'auth:api',
        'image-sanitize'
    ]);
    Route::post('/{id}/publish', [PostController::class, 'publish'])->middleware([
        'auth:api'
    ]);
    Route::post('/{id}/delete', [PostController::class, 'destroy'])->middleware([
        'auth:api'
    ]);
});

// Search

Route::group(['prefix' => 'search'], function() {
    Route::get('/posts', [SearchController::class, 'posts']);
});

// Comments

Route::group(['prefix' => 'comments'], function () {
    Route::post('/', [CommentController::class, 'store'])
        ->middleware('auth:api');

    // Non Refactored

    Route::post('/{id}', [CommentController::class, 'update']);
});
