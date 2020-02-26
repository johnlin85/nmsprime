<?php

namespace Modules\ProvHA\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;
use Modules\ProvHA\Entities\ProvHA;
use Storage;

class SyncHAMasterFiles extends Command
{

    private $sync_user = 'root';
    private $sync_paths = [
        '/etc/named-ddns-cpe.key' => '/etc',
        '/tftpboot/cvc' => '/tftpboot',
        '/tftpboot/fw' => '/tftpboot',
        '/tftpboot/keyfile' => '/tftpboot',
        // do not sync /var/www/nmsprime/storage/app/data/provha – content differs on master and slave machines
        '/var/www/nmsprime/storage/app/config' => '/var/www/nmsprime/storage/app',
        '/var/www/nmsprime/storage/app/data/billingbase' => '/var/www/nmsprime/storage/app/data',
        '/var/www/nmsprime/storage/app/data/dashboard' => '/var/www/nmsprime/storage/app/data',
        '/var/www/nmsprime/storage/app/data/hfccustomer' => '/var/www/nmsprime/storage/app/data',
        '/var/www/nmsprime/storage/app/data/hfcsnmp' => '/var/www/nmsprime/storage/app/data',
        '/var/www/nmsprime/storage/app/data/provvoipenvia' => '/var/www/nmsprime/storage/app/data',
    ];
    private $sync_cmd = '/usr/bin/rsync';
    /* private $sync_opts = '-avrpEogl --delete --dry-run'; */
    private $sync_opts = '-avrpEogl --delete';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'provha:sync_ha_master_files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to sync certain files from master to slave';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        if ('master' != config('provha.hostinfo.own_state')) {
            $msg = "Not a master machine. Exiting ".__CLASS__;
            echo $msg;
            Log::error($msg);
            exit(1);
        }
        $provha = ProvHA::find(1);
        $this->dst_hosts = explode(',', $provha->slaves);
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     *
     * @author Patrick Reichel
     */
    public function handle()
    {
        Log::debug('Begin syncing of master machine to slaves');
        $ssh_port = getenv('SLAVE_SSH_PORT', 22);
        foreach ($this->dst_hosts as $dst_host) {
            foreach ($this->sync_paths as $src_path => $dst_path) {
                if (! file_exists($src_path)) {
                    Log::debug("$src_path does not exist – ignore on syncing to slave");
                    continue;
                }
                $cmd = "$this->sync_cmd $this->sync_opts -e 'ssh -p $ssh_port' $src_path $this->sync_user@$dst_host:$dst_path 2>&1";
                $output = '';
                $retval = -1;
                $this->line('');
                $this->line("Executing $cmd");
                Log::debug("Executing $cmd");
                try {
                    exec($cmd, $output, $retval);
                } catch (\Exception $e) {
                    $this->error("Something went terribly wrong");
                    $msg = "Exception running $cmd: ".$e->getMessage();
                    $this->error($msg);
                    Log::error($msg);
                }
                if ($retval < 0) {
                    $this->error("Error executing command");
                } elseif ($retval == 0) {
                    $this->line(implode("\n", $output));
                } else {
                    $msg = "$cmd exited with exitcode $retval – output:\n\t".implode("\n\t", $output);
                    $this->error($msg);
                    Log::error($msg);
                }
            }
        }
    }

}
