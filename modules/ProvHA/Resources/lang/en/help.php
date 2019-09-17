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
    'load_ratio_master' => 'Defines how load balancing is distributed: 1 means all work is done by master, 0 means all work is done by slaves, 0.5 means master does 50% and slaves share the other 50%. Valid range: [0..1]',
    'master' => 'IP of this host (has to be in [:values])',
    'master_dns_pw' => 'MD5 HMAC; create using: ddns-confgen -a hmac-md5 -r /dev/urandom | grep secret',
    'slave_config_rebuild_interval' => 'Defines how often the configuration files at slave hosts are rebuild. Time is in seconds; will be rounded up to next full minute.',
    'slave_dns_pw' => 'MD5 HMAC; create using: ddns-confgen -a hmac-md5 -r /dev/urandom | grep secret',
    'slaves' => 'IP of slave host.',
];
