<?php
/**
 * Restarts the dhcpd server after checking for correct syntax of the config
 */

$filename = '/var/www/nmsprime/storage/systemd/installd';

// TODO: check content of $filename against storage/app/data/dashboard/modules.php
// This means: do not allow to install or remove other packages than provided in this file!

// yum
exec('yum -y shell '.$filename.' > /tmp/yum.log', $out, $ret);
// TODO: check $ret for success or possible errors..


// Logging
$logfile = '/var/www/nmsprime/storage/logs/laravel.log';

// Log
if (file_exists($logfile)) {
    file_put_contents($logfile, '['.date('Y-m-d H:i:s')."] local.INFO: yum -y shell $filename\n", FILE_APPEND);
} else {
    syslog(LOG_ERR, $logfile.' does not exist ['.__FILE__.']');
}
