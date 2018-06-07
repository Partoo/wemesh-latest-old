<?php
// API
Route::group([
    'middleware' => ['auth:api'],
    'namespace' => 'Stario\\Ihealth\\Controllers\\Api\\V1',
    'prefix' => 'api/v1/health',
], function () {
    Route::resource('aged', 'AgedController', ['except' => ['create', 'edit']]);
});
