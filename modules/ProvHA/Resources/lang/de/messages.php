<?php

/*
|--------------------------------------------------------------------------
| Language lines for module ProvHA
|--------------------------------------------------------------------------
|
| The following language lines are used by the module ProvHA
|
 */

return [
    'env' => [
        'error_hostinfo_own_state' => '„own_state“ muss „master“ oder „slave“ sein (nicht „:0“). provha.env überprüfen und/oder “php artisan config:cache“ ausführen.',
        'host_not_pingable' => '„:host“ scheint offline zu sein (kann nicht angepingt werden).',
        'set_and_determined_state_differ' => 'Der in der .env-Datei konfigurierte Host-Status „:set“ stimmt nicht mit dem ermittelten Status „:determined“ überein.',
        'state_not_set' => 'Host-Status ist nicht konfiguriert in der .env-Datei!',
    ],
    'db_change_forbidden_not_master' => 'Nur der ProvHA-Master darf Änderungen in der Datenbank vornehmen – dies ist ein :state.',
];
