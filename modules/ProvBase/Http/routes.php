<?php

BaseRoute::group([], function () {
    BaseRoute::resource('Modem', 'Modules\ProvBase\Http\Controllers\ModemController');
    BaseRoute::resource('Cmts', 'Modules\ProvBase\Http\Controllers\CmtsController');
    BaseRoute::resource('IpPool', 'Modules\ProvBase\Http\Controllers\IpPoolController');
    BaseRoute::resource('Endpoint', 'Modules\ProvBase\Http\Controllers\EndpointController');
    BaseRoute::resource('Configfile', 'Modules\ProvBase\Http\Controllers\ConfigfileController');
    BaseRoute::resource('Qos', 'Modules\ProvBase\Http\Controllers\QosController');
    BaseRoute::resource('Contract', 'Modules\ProvBase\Http\Controllers\ContractController');
    BaseRoute::resource('Domain', 'Modules\ProvBase\Http\Controllers\DomainController');
    /* BaseRoute::resource('Document', 'Modules\ProvBase\Http\Controllers\DocumentController'); */
    BaseRoute::resource('DocumentTemplate', 'Modules\ProvBase\Http\Controllers\DocumentTemplateController');
    BaseRoute::resource('ProvBase', 'Modules\ProvBase\Http\Controllers\ProvBaseController');

    BaseRoute::get('modem/firmware', [
        'as' => 'Modem.firmware',
        'uses' => 'Modules\ProvBase\Http\Controllers\ModemController@firmware_view',
        'middleware' => ['can:view,Modules\ProvBase\Entities\Modem'],
    ]);

    Route::group(['prefix' => 'api/v{ver}'], function () {
        Route::get('Modem/{Modem}/restart', [
            'as' => 'Modem.api_restart',
            'uses' => 'Modules\ProvBase\Http\Controllers\ModemController@api_restart',
            'middleware' => ['api', 'auth.basic', 'can:update,Modules\ProvBase\Entities\Modem'],
        ]);
    });

    BaseRoute::get('DocumentTemplate/render/{template_id}/{model_id}', [
        'as' => 'DocumentTemplate.render',
        'uses' => 'Modules\ProvBase\Http\Controllers\DocumentTemplateController@render',
        'middleware' => [
            'can:view,Modules\ProvBase\Entities\DocumentTemplate',
            'can:create,Modules\ProvBase\Entities\Document',
            'can:update,Modules\ProvBase\Entities\Document',
        ],
    ]);

    BaseRoute::get('DocumentTemplate/download_pdf/{b64_file}', [
        'as' => 'DocumentTemplate.download_pdf',
        'uses' => 'Modules\ProvBase\Http\Controllers\DocumentTemplateController@download_pdf',
        'middleware' => [
            'can:view,Modules\ProvBase\Entities\DocumentTemplate',
            'can:create,Modules\ProvBase\Entities\Document',
            'can:update,Modules\ProvBase\Entities\Document',
        ],
    ]);
});
