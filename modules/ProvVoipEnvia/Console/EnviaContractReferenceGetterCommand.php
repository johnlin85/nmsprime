<?php

namespace Modules\ProvVoipEnvia\Console;

use Log;
use Illuminate\Console\Command;
use Modules\ProvVoip\Entities\Phonenumber;

/**
 * Class for updating database with carrier codes from csv file
 */
class EnviaContractReferenceGetterCommand extends Command
{
    // get some methods used by several updaters
    use \App\Console\Commands\DatabaseUpdaterTrait;

    /**
     * The console command name.
     */
    protected $name = 'provvoipenvia:get_envia_contract_references';

    /**
     * The console command description.
     */
    protected $description = 'Get envia TEL contract references and write to phonenumbers {default|complete}';

    /**
     * The signature (defining the optional argument)
     */
    protected $signature = 'provvoipenvia:get_envia_contract_references
							{mode=default : The mode to run in; give argument “complete” to get envia TEL references for all (activated and not deactivated) phonenumbers}';

    /**
     * Array containing the phonenumbers we want to get the envia TEL contract references for
     */
    protected $phonenumbers_to_get_contract_reference_for = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        // this comes from config/app.php (key 'url')
        $this->base_url = \Config::get('app.url');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return null
     */
    public function handle()
    {
        Log::info($this->description);

        if (! in_array($this->argument('mode'), ['default', 'complete'])) {
            $_ = 'Usage: '.$this->argument('command').' {default|complete}';
            \Log::error($_);
            echo "$_\n";
            exit(1);
        }

        Log::info('Chosen mode is '.$this->argument('mode'));

        echo "\n";
        $this->_get_phonenumbers($this->argument('mode'));

        echo "\n";
        $this->_get_envia_contract_references();
    }

    /**
     * Collect all phonenumbers we want to get envia TEL contract reference for
     *
     * @author Patrick Reichel
     */
    protected function _get_phonenumbers($mode)
    {
        Log::debug(__METHOD__.' started');

        $phonenumbers = Phonenumber::all();

        // check if we want to get a reference for the phonenumbers
        foreach ($phonenumbers as $phonenumber) {
            $log_number = $phonenumber->id.' ('.$phonenumber->prefix_number.'/'.$phonenumber->number.')';

            // in default mode: don't process numbers without phonenumbermanagements
            // in complete mode: get reference; this will autogenerate a new management
            if ($mode == 'default') {
                // check if number under investigation has a phonenumbermanagement
                if (! $phonenumber->phonenumbermanagement) {
                    Log::debug('Skipping phonenumber '.$log_number.': no phonenumbermanagement');
                    continue;
                }

                // check if active
                if (! $phonenumber->active) {
                    Log::debug('Skipping phonenumber '.$log_number.': not active');
                    continue;
                }

                // check if currently linked envia contract is existing and active
                $envia_contract = $phonenumber->enviacontract;
                if (
                    (! is_null($envia_contract))
                    &&
                    ($envia_contract->state = 'Aktiv')
                ) {
                    Log::debug('Skipping phonenumber '.$log_number.": envia TEL contract $envia_contract->id is active.");
                    continue;
                }
            }

            if ($mode == 'complete') {
                // don't try to get references for numbers without management that are not active
                // doing so would result in an active number (triggered by the autogenerated phonenumbermanagement)
                if (
                    (! $phonenumber->phonenumbermanagement)
                    &&
                    (! $phonenumber->active)
                ) {
                    Log::debug('Skipping phonenumber '.$log_number.': no phonenumbermanagement and number not active');
                    continue;
                }
            }

            // check if deactivation date is more than one week in the past
            $max_deactivation_date = date('Y-m-d', strtotime('-1 week'));
            if (
                ($phonenumber->phonenumbermanagement)
                &&
                ($phonenumber->phonenumbermanagement->deactivation_date)
                &&
                ($phonenumber->phonenumbermanagement->deactivation_date < $max_deactivation_date)
            ) {
                Log::debug('Skipping phonenumber '.$log_number.': deactivation date in the past');
                continue;
            }

            array_push($this->phonenumbers_to_get_contract_reference_for, $phonenumber);
        }
    }

    /**
     * Get all envia TEL contract references for the phonenumbers
     *
     * @author Patrick Reichel
     */
    protected function _get_envia_contract_references()
    {
        Log::debug(__METHOD__.' started');

        foreach ($this->phonenumbers_to_get_contract_reference_for as $phonenumber) {
            $log_number = $phonenumber->id.' ('.$phonenumber->prefix_number.'/'.$phonenumber->number.')';
            $phonenumber_id = $phonenumber->id;
            Log::info("Getting envia contract reference for phonenumber $log_number");

            try {
                // get the relative URL to execute the cron job for updating the current order_id
                $url_suffix = \URL::route('ProvVoipEnvia.cron', ['job' => 'contract_get_reference', 'phonenumber_id' => $phonenumber_id, 'really' => 'True'], false);

                $url = $this->base_url.$url_suffix;

                $this->_perform_curl_request($url);
            } catch (Exception $ex) {
                Log::error('Exception getting envia TEL contract reference for phonenumber '.$log_number.'): '.$ex->getMessage().' => '.$ex->getTraceAsString());
            }
        }
    }
}
