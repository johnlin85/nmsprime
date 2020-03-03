<?php

namespace Modules\ProvHA\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;
use Storage;

class RebuildSlaveConfigCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'provha:rebuild_slave_config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Slave configuration rebuild command';

    protected $last_rebuild_file = '/data/provha/last_slave_rebuild';
    protected $last_rebuild_finished_file = '/data/provha/last_slave_rebuild_finished';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->now = now('UTC');
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
        Log::debug('ProvHA: Entering ' . __METHOD__);
        if ('slave' != config('provha.hostinfo.own_state')) {
            $msg = "Not a slave machine. Exiting " . __CLASS__;
            $this->error($msg);
            Log::error($msg);
            exit(1);
        }
        // if there is no file holding the last rebuild timestamp: (re)build
        if (! Storage::exists($this->last_rebuild_file)) {
            Log::info('ProvHA: Rebuilding slave config: file ' . $this->last_rebuild_file . ' missing');
            $this->rebuildSlaveConfig();
        }

        $provha_config = \DB::table('provha')->first();
        $provha_rebuild_interval = $provha_config->slave_config_rebuild_interval;
        $last_rebuild = Carbon::parse(Storage::get($this->last_rebuild_file));
        $seconds_correct = 30;
        $compare_time = $this->now
                             ->copy()   // we need a copy as this variable changes in lt($now->subSeconds)…
                             ->subSeconds($provha_rebuild_interval)
                             ->addSeconds($seconds_correct);

        // check for cyclic rebuild
        if ($last_rebuild->lt($compare_time)) {
            Log::info('ProvHA: Rebuilding slave config: More than ' . $provha_rebuild_interval . ' seconds since last rebuild');
            $this->rebuildSlaveConfig(true);
        }

        // check for extra rebuild (necessary on changes in certain tables):
        //  * provha
        //  * netgw
        //  * ippools
        $provha_update = Carbon::parse($provha_config->updated_at);
        if ($last_rebuild->lt($provha_update)) {
            Log::info('ProvHA: Rebuilding slave config: ProvHA config in database updated');
            $this->rebuildSlaveConfig(true);
            return;
        }
        $check_tables = [
            'netgw',
            'ippool',
            'modem',
            'mta',
            'endpoint',
        ];
        foreach ($check_tables as $table) {
            $entry = \DB::table($table)->orderBy('updated_at', 'desc')->first();
            if (! $entry) {
                continue;
            };
            $last_table_update = Carbon::parse($entry->updated_at);
            if ($last_rebuild->lt($last_table_update)) {
                Log::info('ProvHA: Rebuilding slave config: Database table “' . $table . '” updated after last rebuild');
                $this->rebuildSlaveConfig(false);
                return;
            }
        }
    }

    /**
     * Wrapper to trigger the single rebuilds.
     *
     * @param   bool    $include_configfiles    Flag to indicate if config files shall be rebuilt, too
     *
     * @author Patrick Reichel
     */
    protected function rebuildSlaveConfig($include_configfiles = false)
    {
        Storage::put($this->last_rebuild_file, $this->now->toIso8601String());
        if ($include_configfiles) {
            \Artisan::call('nms:configfile');
        }
        \Artisan::call('nms:dhcp');
        Storage::put($this->last_rebuild_finished_file, now('UTC')->toIso8601String());
    }
}
