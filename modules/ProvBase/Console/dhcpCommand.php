<?php

namespace Modules\provbase\Console;

use Illuminate\Console\Command;
use Modules\ProvVoip\Entities\Mta;
use Modules\ProvBase\Entities\Modem;
use Modules\ProvBase\Entities\NetGw;
use Modules\ProvBase\Entities\Endpoint;
use Modules\ProvBase\Entities\ProvBase;

class dhcpCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nms:dhcp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make the DHCP config';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command - Create global Config & all Entries for Modems, Endpoints & Mtas to get an IP from Server
     *
     * @return mixed
     */
    public function handle()
    {
        // Global Config part
        $prov = ProvBase::first();
        $prov->make_ddns_conf();
        $prov->make_dhcp_glob_conf();
        $prov->make_dhcp_default_network_conf();

        Modem::make_dhcp_cm_all();
        Modem::create_ignore_cpe_dhcp_file();
        Endpoint::make_dhcp();

        if (\Module::collections()->has('ProvVoip') && \Schema::hasTable('mta')) {
            Mta::make_dhcp_mta_all();
        }

        // don't run this command during a new installation
        // this is needed, due to cmts to netgw renaming
        $table = (new \ReflectionClass(NetGw::class))->getDefaultProperties()['table'];
        if (\Schema::hasTable($table)) {
            NetGw::make_dhcp_conf_all();
        }

        // check if we have to build failover conf
        if (
            (\Module::collections()->has('ProvHA'))
            &&
            // check if master or slave
            (in_array(config('provha.hostinfo.own_state'), ['master', 'slave']))
        ) {
            \Modules\ProvHA\Entities\ProvHA::make_dhcp_failover_conf();
        }

        // Restart dhcp server
        $dir = storage_path('systemd/');
        if (! is_dir($dir)) {
            mkdir($dir, 0700, true);
            chown($dir, 'apache');
        }
        touch($dir.'dhcpd');
    }
}
