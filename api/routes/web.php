<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (env('APP_ENV') === 'local') {
    Route::get('test', [Controller::class, 'test']);
}

Route::get('sitemap.xml', function () {
    echo 'test';
});

Route::get('/{any}', function () {
    return view('quasar');
})
    ->where('any', '.*');
