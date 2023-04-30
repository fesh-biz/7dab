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
});

// Users

Route::group(['prefix' => 'users'], function () {
    Route::get('{id}', [UserController::class, 'find']);
    Route::get('{id}/stats', [UserController::class, 'stats']);
});

// Profile

Route::group(['prefix' => 'profile', 'middleware' => 'auth:api'], function () {
    Route::get('content-stats', [ProfileController::class, 'contentStats']);
    Route::get('posts', [PostController::class, 'profilePosts']);
    Route::get('comments', [CommentController::class, 'profileComments']);
    Route::get('answers', [CommentController::class, 'profileAnswers']);
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

Route::group(['prefix' => 'comments', 'as' => '.comments'], function () {
    Route::get('/post-comments', [CommentController::class, 'postComments']);
    Route::post('/', [CommentController::class, 'store'])
        ->name('.create')->middleware('auth:api');

    // Non Refactored

    Route::post('/{id}', [CommentController::class, 'update'])
        ->name('.update');
});
