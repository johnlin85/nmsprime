<?php

// this file seems to be included multiple times (e.g. in “php artisan route:cache”)
// to avoid redeclaration of this function check if already defined
if (!function_exists('collectOwnData')) {
    function collectOwnData()
    {
        $ret = [];

        $hostname = gethostname();
        $ips_raw = trim(`hostname -I`);
        $ips = [];
        foreach (explode(' ', $ips_raw) as $ip) {
            // ATM only IPv4 addresses are supported
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $ips[] = trim($ip);
            }
        }


        // extend env by own IPs and hostname
        $ret['own_hostname'] = $hostname;
        $ret['own_ips'] = $ips;
        $ret['own_hostname_and_ips'] = array_merge([$hostname], $ips);
        $ret['own_state_determined'] = 'n/a';

        // there is no table if migrating
        if (\Schema::hasTable('provha')) {
            $provha_config = \DB::table('provha')->first();
            if (in_array($provha_config->master, $ret['own_hostname_and_ips'])) {
                $ret['own_state_determined'] = 'master';
            } else {
                $ret['own_state_determined'] = 'unknown';
                $slaves = explode(',', $provha_config->slaves);
                foreach ($slaves as $slave) {
                    if (in_array(trim($slave), $ret['own_hostname_and_ips'])) {
                        $ret['own_state_determined'] = 'slave';
                    }
                }
            }
        }

        // check if own state is set in provha.env
        $ret['own_state'] = strtolower(env('PROVHA__OWN_STATE', ''));
        if (! $ret['own_state']) {
            \Log::critical('ProvHA: own state not set in provha.conf. Determined state is “' . $ret['own_state_determined'] . '”');
        } elseif ($ret['own_state'] != $ret['own_state_determined']) {
            \Log::critical('ProvHA: Configuration mismatch: .env configured host state (“' . $ret['own_state'] . '”) does not match determined host state (“' . $ret['own_state_determined'] . '”)');
        } else {
            \Log::debug('ProvHA: Host state is “' . $ret['own_state'] . '”');
        }

        return $ret;
    }
}

$ret = [];
$ret['hostinfo'] = collectOwnData();

$ret['name'] = 'ProvHA';

return $ret;
