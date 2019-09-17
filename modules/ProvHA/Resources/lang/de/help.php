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
    'load_ratio_master' => 'Legt fest, in welchem Verhältnis die Last verteilt wird: Bei 1 erledigt der Master alle Anfragen, bei 0 die Slaves, bei 0.5 erledigt der Master 50% und die Slaves teilen sich die restlichen 50%. Gültige Werte liegen im Intervall [0..1].',
    'master' => 'IP dieser Maschine (mögliche Werte: [:values])',
    'master_dns_pw' => 'MD5-HMAC; Generierung mittels: ddns-confgen -a hmac-md5 -r /dev/urandom | grep secret',
    'slave_config_rebuild_interval' => 'Legt fest, in welchen Abständen die Konfigurationsdateien auf den Slave-Servern neu gebaut werden. Angabe in Sekunden; wird auf volle nächste Minute gerundet.',
    'slave_dns_pw' => 'MD5-HMAC; Generierung mittels: ddns-confgen -a hmac-md5 -r /dev/urandom | grep secret',
    'slaves' => 'IP der redundanten Maschine.',
];
