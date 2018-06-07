<?php

use Stario\Iwrench\Weather\WeatherService;

// web
Route::get('/', function () {
    return view('icenter::admin.home');
})->middleware('web');

Route::post('service/upload', 'Stario\\Iwrench\\FileManager\\Uploader@upload');
// api
Route::group([
    'middleware' => ['auth:api'],
    'namespace' => 'Stario\\Icenter\\Controllers\\Api\\V1',
    'prefix' => 'api/v1',
], function () {
    Route::resource('admin', 'AdminController', ['except' => ['create', 'edit']]);
    Route::resource('menu', 'MenuController', ['except' => ['create', 'edit']]);
    Route::get('account', 'MeController@index');
});

Route::group([
    'middleware' => ['api'],
    'namespace' => 'Stario\\Icenter\\Controllers\\Api\\V1',
    'prefix' => 'api/v1',
], function () {
    Route::post('auth', 'AdminAuthController@login');
    Route::post('auth/refresh', 'AdminAuthController@refreshToken');
    Route::post('auth/register', 'AdminAuthController@register');
    Route::get('weather', function () {
        return WeatherService::get();
    });
});
