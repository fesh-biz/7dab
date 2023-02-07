<?php

Route::group(['prefix' => 'tags'], function () {
    Route::get('/', function () {
        return 'yep';
    });
});