<?php

namespace Modules\ProvHA\Entities;

class ProvHA extends \BaseModel
{
    // The associated SQL table for this Model
    protected $table = 'provha';

    // Add your validation rules here
    public static function rules($id = null)
    {
        return [
            'master' => 'required|hostname_or_ip|placeholder__is_this_machine',
            'slaves' => 'nullable|comma_separated_hostnames_or_ips',
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

        self::observe(new ProvHAObserver);
    }

    public function verify_settings()
    {
        // check if this host's state is set and determined correctly
        if (! env('PROVHA__OWN_STATE')) {
            $msg = 'ProvHA: '.trans('provha::messages.env.state_not_set');
            $this->addAboveMessage($msg, 'error', $place='form');
        } elseif (env('PROVHA__OWN_STATE') != env('PROVHA__OWN_STATE_DETERMINED')) {
            $msg = 'ProvHA: '.trans('provha::messages.env.set_and_determined_state_differ', ['set' => env('PROVHA__OWN_STATE'), 'determined' => env('PROVHA__OWN_STATE_DETERMINED')]);
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
        // rebuild master configuration

        // rebuild all slave configurations
    }
}
