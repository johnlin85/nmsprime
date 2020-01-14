<?php

namespace Modules\ProvVoipEnvia\Entities;

use Modules\ProvBase\Entities\Modem;
use Modules\ProvBase\Entities\Contract;
use Modules\ProvVoip\Entities\Phonenumber;

class EnviaContract extends \BaseModel
{
    // get DataTables functions
    use DtFunctionsTrait;

    // The associated SQL table for this Model
    public $table = 'enviacontract';

    // do not auto delete anything related to envia (can e.g. be contracts and modems)
    protected $delete_children = false;

    protected $fillable = [];

    // Name of View
    public static function view_headline()
    {
        return 'envia TEL contract';
    }

    public static function view_icon()
    {
        return '<i class="fa fa-handshake-o"></i>';
    }

    // link title in index view
    public function view_index_label()
    {
        $envia_contract_reference = $this->get_envia_contract_reference();
        $bsclass = $this->get_bsclass();

        return ['table'     => $this->table,
            'index_header'  => [$this->table.'.envia_contract_reference', $this->table.'.state', $this->table.'.start_date', $this->table.'.end_date', 'contract.id', 'modem.id'],
            'bsclass'       => $bsclass,
            'eager_loading' => ['contract', 'modem'],
            'edit'          => ['envia_contract_reference' => 'get_envia_contract_reference', 'state' => 'get_state', 'start_date' => 'get_start_date', 'end_date' => 'get_end_date', 'contract.id' => 'get_contract_data', 'modem.id' => 'get_modem_data'],
            'header'        => $envia_contract_reference,
            'filter'        => [
                'contract.id' => $this->get_contract_filtercolumn_query(),
                'modem.id'    => $this->get_modem_filtercolumn_query(),
            ],
            'raw_columns' => ['contract.id', 'modem.id'],
        ];
    }

    public function get_bsclass()
    {
        $state = is_null($this->state) ? '–' : $this->state;

        if (in_array($state, ['Aktiv'])) {
            $bsclass = 'success';
        } elseif (in_array($state, ['Gekündigt'])) {
            $bsclass = 'danger';
        } elseif (in_array($state, ['In Realisierung'])) {
            $bsclass = 'warning';
        } else {
            $bsclass = 'info';
        }

        return $bsclass;
    }

    public function get_state()
    {
        $state = is_null($this->state) ? '–' : $this->state;

        return $state;
    }

    public function get_start_date()
    {
        $start_date = is_null($this->start_date) ? '–' : $this->start_date;

        return $start_date;
    }

    public function get_end_date()
    {
        $end_date = is_null($this->end_date) ? '–' : $this->end_date;

        return $end_date;
    }

    public function get_envia_contract_reference()
    {
        $envia_contract_reference = is_null($this->envia_contract_reference) ? '–' : $this->envia_contract_reference;

        return $envia_contract_reference;
    }

    /* // View Relation. */
    /* public function view_has_many() */
    /* { */
    /* 	$ret = array(); */
    /* 	$ret['Edit']['Contract'] = $this->contract; */
    /* 	/1* $ret['Edit']['Modem'] = $this->modem; *1/ */

    /* 	return $ret; */
    /* } */

    // the relations

    /**
     * Link to contract
     */
    public function contract()
    {
        return $this->belongsTo(\Modules\ProvBase\Entities\Contract::class, 'contract_id');
    }

    /**
     * Link to modem
     */
    public function modem()
    {
        return $this->belongsTo(\Modules\ProvBase\Entities\Modem::class, 'modem_id');
    }

    /**
     * Link to enviaorders
     */
    public function enviaorders()
    {
        return $this->hasMany(EnviaOrder::class, 'enviacontract_id');
    }

    /**
     * Link to phonenumbermanagements
     */
    public function phonenumbermanagements()
    {
        return $this->hasMany(\Modules\ProvVoip\Entities\PhonenumberManagement::class, 'enviacontract_id');
    }

    /**
     * Link to phonenumbers
     */
    public function phonenumbers()
    {
        $phonenumbers = [];
        foreach ($this->phonenumbermanagements as $mgmt) {
            array_push($phonenumbers, $mgmt->phonenumber);
        }

        return collect($phonenumbers);
        /* return $this->hasManyThrough(\Modules\ProvVoip\Entities\Phonenumber::class, \Modules\ProvVoip\Entities\PhonenumberManagement::class, 'enviacontract_id'); */
        /* return $this->hasManyThrough(\Modules\ProvVoip\Entities\Phonenumber::class, \Modules\ProvVoip\Entities\PhonenumberManagement::class); */
    }

    /**
     * Gets all phonenumbers with:
     *		- existing phoneunmbermanagement
     *		- activation date less or equal than today
     *		- deactivation date null or bigger than today
     *
     * @author Patrick Reichel
     */
    public function phonenumbers_active_through_phonenumbermanagent()
    {
        $phonenumbers = $this->phonenumbers();

        $isodate = substr(date('c'), 0, 10);

        $ret = [];
        foreach ($phonenumbers as $phonenumber) {
            $mgmt = $phonenumber->phonenumbermanagement;

            // activation date not set
            if (is_null($mgmt->activation_date)) {
                continue;
            }

            // not yet activated
            if ($mgmt->activation_date > $isodate) {
                continue;
            }

            // deactivation date set and today or in the past
            if (
                (! is_null($mgmt->deactivation_date))
                && ($mgmt->deactivation_date <= $isodate)
            ) {
                continue;
            }

            // number seems to be active
            array_push($ret, $phonenumber);
        }

        return $ret;
    }

    /**
     * Build an array containing all relations of this contract for edit view
     *
     * @author Patrick Reichel
     */
    public function get_relation_information()
    {
        $relations = [];
        $relations['head'] = '';
        $relations['hints'] = [];
        $relations['links'] = [];

        if ($this->contract_id) {
            $contract = Contract::withTrashed()->find($this->contract_id);
            $relations['hints'][trans('provvoipenvia::view.enviaContract.contract')] = ProvVoipEnviaHelpers::get_user_action_information_contract($contract);
        }

        if ($this->modem_id) {
            $modem = Modem::withTrashed()->find($this->modem_id);
            $relations['hints'][trans('provvoipenvia::view.enviaContract.modem')] = ProvVoipEnviaHelpers::get_user_action_information_modem($modem);
        }

        $mgmts = $this->phonenumbermanagements;
        if ($mgmts) {
            $phonenumbers = [];
            foreach ($mgmts as $mgmt) {
                array_push($phonenumbers, $mgmt->phonenumber);
            }
            $this->phonenumbers = collect($phonenumbers);
            $relations['hints'][trans('provvoipenvia::view.enviaContract.phonenumbers')] = ProvVoipEnviaHelpers::get_user_action_information_phonenumbers($this, $this->phonenumbers);
        }

        if ($this->enviaorders) {
            $relations['hints'][trans('provvoipenvia::view.enviaContract.orders')] = ProvVoipEnviaHelpers::get_user_action_information_enviaorders($this->enviaorders->sortBy('orderdate'));
        }

        return $relations;
    }

    /**
     * We do not delete envia TEL contracts directly (e.g. on deleting a phonenumber).
     * This is later done using a cronjob that deletes all orphaned contracts.
     *
     * This method will return true so that related models can be deleted.
     *
     * @author Patrick Reichel
     */
    public function delete()
    {
        $msg = trans('provvoipenvia::messages.enviaContractDeletedByCron');
        $this->addAboveMessage($msg, 'info');

        return true;
    }
}
