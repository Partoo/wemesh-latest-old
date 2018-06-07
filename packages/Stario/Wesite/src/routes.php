<?php

use Stario\Wesite\Models\Post;
// Backend API
Route::group([
    'middleware' => ['auth:api'],
    'namespace' => 'Stario\\Wesite\\Controllers\\Api\\V1',
    'prefix' => 'api/v1/wesite/admin',
], function () {
    Route::resource('post', 'PostController', ['except' => ['create', 'edit', 'destroy']]);
    Route::resource('page', 'PageController', ['except' => ['create', 'edit', 'destroy']]);
    Route::resource('widget', 'WidgetController', ['except' => ['create', 'edit', 'destroy']]);
    Route::post('post/delete', 'PostController@destroy');
    Route::post('post/update-status', 'PostController@updateStatus');
    Route::resource('menu', 'MenuController', ['except' => ['create', 'edit']]);
    Route::post('menu/reorder/', 'MenuController@reorder');
    Route::resource('category', 'CategoryController', ['except' => ['create', 'edit']]);
});

// Frontend API

Route::group([
    // 'middleware' => ['wx'],
    'namespace' => 'Stario\\Wesite\\Controllers\\Api\\V1',
    'prefix' => 'api/v1/wesite',
], function () {
    Route::get('home', 'FrontController@home');
    Route::get('category/{id}', 'FrontController@category');
    Route::get('post/{id}', 'FrontController@post');
});
