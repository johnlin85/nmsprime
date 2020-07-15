<?php

BaseRoute::group([], function () {
    BaseRoute::resource('Modem', 'Modules\ProvBase\Http\Controllers\ModemController');
    BaseRoute::resource('NetGw', 'Modules\ProvBase\Http\Controllers\NetGwController');
    BaseRoute::resource('IpPool', 'Modules\ProvBase\Http\Controllers\IpPoolController');
    BaseRoute::resource('Endpoint', 'Modules\ProvBase\Http\Controllers\EndpointController');
    BaseRoute::resource('Configfile', 'Modules\ProvBase\Http\Controllers\ConfigfileController');
    BaseRoute::resource('Qos', 'Modules\ProvBase\Http\Controllers\QosController');
    BaseRoute::resource('Contract', 'Modules\ProvBase\Http\Controllers\ContractController');
    BaseRoute::resource('Domain', 'Modules\ProvBase\Http\Controllers\DomainController');
    BaseRoute::resource('ProvBase', 'Modules\ProvBase\Http\Controllers\ProvBaseController');

    BaseRoute::get('modem/firmware', [
        'as' => 'Modem.firmware',
        'uses' => 'Modules\ProvBase\Http\Controllers\ModemController@firmware_view',
        'middleware' => ['can:view,Modules\ProvBase\Entities\Modem'],
    ]);

    BaseRoute::get('Configfile/{id}/refreshgenieacs', [
        'as' => 'Configfile.refreshGenieAcs',
        'uses' => 'Modules\ProvBase\Http\Controllers\ConfigfileController@refreshGenieAcs',
        'middleware' => ['can:update,Modules\ProvBase\Entities\Configfile'],
    ]);

    BaseRoute::get('Configfile/{id}/searchdeviceparams', [
        'as' => 'Configfile.searchDeviceParams',
        'uses' => 'Modules\ProvBase\Http\Controllers\ConfigfileController@searchDeviceParams',
        'middleware' => ['can:update,Modules\ProvBase\Entities\Configfile'],
    ]);

    Route::group(['prefix' => 'api/v{ver}'], function () {
        Route::get('Modem/{Modem}/restart', [
            'as' => 'Modem.api_restart',
            'uses' => 'Modules\ProvBase\Http\Controllers\ModemController@api_restart',
            'middleware' => ['api', 'auth.basic', 'can:update,Modules\ProvBase\Entities\Modem'],
        ]);
    });
});
