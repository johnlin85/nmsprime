<?php

Route::group(['middleware' => 'web', 'prefix' => 'provha', 'namespace' => 'Modules\ProvHA\Http\Controllers'], function()
{
    Route::get('/', 'ProvHAController@index');
});
