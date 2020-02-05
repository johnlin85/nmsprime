<?php

namespace Modules\ProvHA\Entities;

use File;

class ProvHA extends \BaseModel
{
    // The associated SQL table for this Model
    protected $table = 'provha';

    // Add your validation rules here
    public static function rules($id = null)
    {
        return [
            // outcommented validation rules may be used later – if hostnames can be used or if multiple slaves are supported
            /* 'master' => 'required|hostname_or_ip|placeholder__is_this_machine', */
            /* 'slaves' => 'nullable|comma_separated_hostnames_or_ips', */
            'master' => 'required|ipv4|placeholder__is_this_machine',
            'slaves' => 'nullable|ipv4',
            'load_ratio_master' => 'required|numeric|between:0,1',
            'slave_config_rebuild_interval' => 'required|integer|between:60,604800', // 1min..1week
        ];
    }

    // Name of View
    public static function view_headline()
    {
        return 'ProvHA Config';
    }

    // link title in index view
    public function view_index_label()
    {
        return 'ProvHA';
    }

    // View Icon
    public static function view_icon()
    {
        return '<i class="fa fa-server"></i>';
    }

    public static function boot()
    {
        parent::boot();

        self::observe(new \App\SystemdObserver);
        self::observe(new ProvHAObserver);
    }

    public function verify_settings()
    {
        // check if this host's state is set and determined correctly
        if (! config('provha.hostinfo.own_state')) {
            $msg = 'ProvHA: '.trans('provha::messages.env.state_not_set');
            $this->addAboveMessage($msg, 'error', $place='form');
        } elseif (config('provha.hostinfo.own_state') != config('provha.hostinfo.own_state_determined')) {
            $msg = 'ProvHA: '.trans('provha::messages.env.set_and_determined_state_differ', ['set' => config('provha.hostinfo.own_state'), 'determined' => config('provha.hostinfo.own_state_determined')]);
            $this->addAboveMessage($msg, 'error', $place='form');
        }

        // check if master/slaves are pingable
        $hosts = explode(',', $this->slaves);
        array_push($hosts, $this->master);
        foreach ($hosts as $host) {
            if ($host) {
                exec('sudo ping -c1 -i0 -w1 '.$host, $ping, $offline);
                if ($offline) {
                    $msg = 'ProvHA: '.trans('provha::messages.env.host_not_pingable', ['host' => $host]);
                    $this->addAboveMessage($msg, 'warning', $place='form');
                }
            }
        }
    }

    /**
     * Create dhcp config for failover.
     *
     * @author Patrick Reichel
     */
    public static function make_dhcp_failover_conf()
    {
        $filename = '/etc/dhcp-nmsprime/failover.conf';

        // lock
        $fp = fopen($filename, 'r+');

        if (! flock($fp, LOCK_EX)) {
            Log::error('Could not get exclusive lock for '.$filename);
        }

        try {
            $data_in = File::get($filename);
        } catch (\Exception $e) {
            $msg = trans('messages.error_reading_file', [$filename]).'<br> ⇒ '.$e->getMessage();
            \Session::push('tmp_error_above_form', $msg);
            \Log::error("Error reading $filename ".$e->getMessage());
            return;
        }

        $provha = ProvHA::first();

        $master = $provha->master;
        $slave = explode(',', $provha->slaves)[0] ?: 'slave.not.set.localdomain';

        if ('master' == config('provha.hostinfo.own_state')) {
            $level = 'primary';
            $own_ip = $master;
            $peer_ip = $slave;
        } else {
            $level = 'secondary';
            $own_ip = $slave;
            $peer_ip = $master;
        }

        // the regexes and the replacement strings later used in preg_replace
        $regexes = [
            [
                '/(^\s*)(primary|secondary)(;.*)$/m',
                '$1'.$level.'$3'
            ],
            [
                '/(^\s*address )([\d\w\.\<\>\_]*)(;.*)$/m',
                '${1}'.$own_ip.'${3}'   // attention: curly brackets around backreferences are essential here
            ],
            [
                '/(^\s*peer address )([\d\w\.\<\>\_]*)(;.*)$/m',
                '${1}'.$peer_ip.'${3}'  // attention: curly brackets around backreferences are essential here
            ],
        ];
        $data_out = $data_in;
        foreach ($regexes as $regex) {
            $data_out = preg_replace($regex[0], $regex[1], $data_out, 1);
        }

        if ($data_out != $data_in) {
            try {
                \Log::info("ProvHA: Writing changes to $filename");
                File::put($filename, $data_out);
            }
            catch (\Exception $ex) {
                $msg = trans('messages.error_writing_file', [$filename]).'<br> ⇒ '.$e->getMessage();
                \Session::push('tmp_error_above_form', $msg);
                \Log::error("Error writing $filename ".$e->getMessage());
                return;
            }
        }

        // unlock
        flock($fp, LOCK_UN);
        fclose($fp);

    }

}

class ProvHAObserver
{
    /**
     * Perform actions after changing the config.
     *
     * @author Patrick Reichel
     */
    public function updated($provha)
    {
        \Log::debug('ProvHA: Settings changed, will create new DHCPd config');
        $provha::make_dhcp_failover_conf();
    }
}
