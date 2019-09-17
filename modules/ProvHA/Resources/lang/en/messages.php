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
        'error_hostinfo_own_state' => '“own_state” has to be “master” or “slave” – currently set to “:0”. Update provha.env and/or run “php artisan config:cache“.',
        'host_not_pingable' => '“:host” seems to be offline (cannot be pinged).',
        'set_and_determined_state_differ' => '.env configured host state “:set” does not match determined host state “:determined”.',
        'state_not_set' => 'Host state not set in .env file!',
    ],
    'db_change_forbidden_not_master' => 'Only the ProvHA master is allowed to change the database, this is a :state.',
];
