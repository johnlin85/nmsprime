<?php

BaseRoute::group([], function () {
    BaseRoute::resource('ProvHA', 'Modules\ProvHA\Http\Controllers\ProvHAController');
});

