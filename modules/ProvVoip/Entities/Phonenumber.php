<?php

namespace Modules\ProvVoip\Entities;

use Illuminate\Support\Collection;

// Model not found? execute composer dump-autoload in nmsprime root dir
class Phonenumber extends \BaseModel {

    // The associated SQL table for this Model
    public $table = 'phonenumber';

	// Add your validation rules here
	public static function rules($id=null)
	{
		$ret = array(
			'country_code' => 'required|numeric',
			'prefix_number' => 'required|numeric',
			'number' => 'required|numeric',
			'mta_id' => 'required|exists:mta,id|min:1',
			'port' => 'required|numeric|min:1',
			/* 'active' => 'required|boolean', */
			// TODO: check if password is secure and matches needs of external APIs (e.g. envia TEL)
		);

		// inject id to rules (so it is passed to prepare_rules)
		$ret['id'] = $id;

		return $ret;
	}


	// Name of View
	public static function view_headline()
	{
		return 'Phonenumbers';
	}

	// View Icon
	public static function view_icon()
	{
		return '<i class="fa fa-list-ol"></i>';
	}

	// AJAX Index list function
	// generates datatable content and classes for model
	public function view_index_label()
	{
		$bsclass = $this->get_bsclass();

		return ['table' => $this->table,
				'index_header' => [$this->table.'.number', 'phonenumbermanagement.activation_date', 'phonenumbermanagement.deactivation_date', 'phonenr_state'],
				'header' => 'Port '.$this->port.': '.$this->prefix_number."/".$this->number,
				'bsclass' => $bsclass,
				'edit' => ['phonenumbermanagement.activation_date' => 'get_act', 'phonenumbermanagement.deactivation_date' => 'get_deact', 'phonenr_state' => 'get_state', 'number' => 'build_number'],
				'eager_loading' => ['phonenumbermanagement'],
				'sortsearch' => ['phonenr_state' => 'false'],
				'filter' => ['phonenumber.number' => $this->number_query(), ] ];
	}

	public function number_query()
	{
		return "CONCAT(phonenumber.prefix_number,'/',phonenumber.number) like ?";
	}

	public function get_bsclass()
	{
		$management = $this->phonenumbermanagement;
		if (is_null($management)) {
			$bsclass = 'warning';
			}
		else {
			$act = $management->activation_date;
			$deact = $management->deactivation_date;
			// deal with legacy problem of zero dates
			if (!boolval($act)) {
				$bsclass = 'danger';
			}
			elseif ($act > date('c')) {
				$bsclass = 'warning';
			}
			else {
				if (!boolval($deact)) {
					$bsclass = 'success';
				}
				else {
					if ($deact > date('c')) {
						$state = 'Active. Deactivation date set but not reached yet.';
						$bsclass = 'warning';
					}
					else {
						$state = 'Deactivated.';
						$bsclass = 'info';
					}
				}
			}
		}

		return $bsclass;
	}

	public function get_state()
	{
		$management = $this->phonenumbermanagement;

		if (is_null($management)) {
			if ($this->active) {
				$state = 'Active.';
			}
			else {
				$state = 'Deactivated.';
			}
			$state .= ' No PhonenumberManagement existing!';
		}
		else {
			$act = $management->activation_date;
			$deact = $management->deactivation_date;

			if (!boolval($act)) {
				$state = 'No activation date set!';
			}
			elseif ($act > date('c')) {
				$state = 'Waiting for activation.';
			}
			else {
				if (!boolval($deact)) {
					$state = 'Active.';
				}
				else {
					if ($deact > date('c')) {
						$state = 'Active. Deactivation date set but not reached yet.';
					}
					else {
						$state = 'Deactivated.';
					}
				}
			}

			if (boolval($management->autogenerated)) {
				$state .= ' – PhonenumberManagement generated automatically!';
			}
		}
		return $state;
	}

	public function get_act()
	{
		$management = $this->phonenumbermanagement;

		if (is_null($management)) {
			$act = 'n/a';
		}
		else {
			$act = $management->activation_date;

			if ($act == '0000-00-00') {
				$act = null;
			}
		}

		// reuse dates for view
		if (is_null($act)) $act = '-';

		return $act;
	}

	public function get_deact()
	{
		$management = $this->phonenumbermanagement;

		if (is_null($management)) {
			$deact = 'n/a';
		}
		else {
			$deact = $management->deactivation_date;

			if ($deact == '0000-00-00') {
				$deact = null;
			}
		}

		// reuse dates for view
		if (is_null($deact)) $deact = '-';

		return $deact;
	}

	public function build_number()
	{
		return $this->prefix_number.'/'.$this->number;
	}

	/**
	 * ALL RELATIONS
	 * link with mtas
	 */
	public function mta()
	{
		return $this->belongsTo('Modules\ProvVoip\Entities\Mta', 'mta_id');
	}

	// belongs to an mta
	public function view_belongs_to ()
	{
		return $this->mta;
	}

	// View Relation.
	public function view_has_many()
	{
		$ret = array();
		if (\PPModule::is_active('provvoip')) {

			$relation = $this->phonenumbermanagement;

			// can be created if no one exists, can be deleted if one exists
			if (is_null($relation)) {
				$ret['Main']['PhonenumberManagement']['relation'] = new Collection();
				$ret['Main']['PhonenumberManagement']['options']['hide_delete_button'] = 1;
			}
			else {
				$ret['Main']['PhonenumberManagement']['relation'] = [$relation];
				$ret['Main']['PhonenumberManagement']['options']['hide_create_button'] = 1;
			}

			$ret['Main']['PhonenumberManagement']['class'] = 'PhonenumberManagement';
		}

		if (\PPModule::is_active('provvoipenvia')) {
			// TODO: auth - loading controller from model could be a security issue ?
			$ret['Main']['envia TEL API']['html'] = '<h4>Available envia TEL API jobs</h4>';
			$ret['Main']['envia TEL API']['view']['view'] = 'provvoipenvia::ProvVoipEnvia.actions';
			$ret['Main']['envia TEL API']['view']['vars']['extra_data'] = \Modules\ProvVoip\Http\Controllers\PhonenumberController::_get_envia_management_jobs($this);
		}

		if (\PPModule::is_active('voipmon')) {
			$ret['Monitoring']['Cdr'] = $this->cdrs;
		}

		return $ret;
	}

	/**
	 * return all mta objects
	 */
	public function mtas()
	{
		$dummies = Mta::withTrashed()->where('is_dummy', True)->get();
		$mtas = Mta::get();
		return array('dummies' => $dummies, 'mtas' => $mtas);
	}

	/**
	 * return a list [id => hostname] of all mtas
	 */
	public function mtas_list()
	{
		$ret = array();
		foreach ($this->mtas()['mtas'] as $mta)
		{
			$ret[$mta->id] = $mta->hostname;
		}

		return $ret;
	}

	/**
	 * return a list [id => hostname] of all mtas
	 */
	public function mtas_list_with_dummies()
	{
		$ret = array();
		foreach ($this->mtas() as $mta_tmp)
		{
			foreach ($mta_tmp as $mta)
			{
				$ret[$mta->id] = $mta->hostname;
			}
		}

		return $ret;
	}


	/**
	 * return a list [id => hostname, mac and contract information] of all mtas assigned to a contract
	 */
	public function mtas_list_only_contract_assigned()
	{
		$ret = array();

		$mtas = \DB::table('mta')
			->join('modem as m', 'm.id', '=', 'mta.modem_id')->join('contract as c', 'c.id', '=', 'm.contract_id')
			->where('m.deleted_at', '=', null)->where('c.deleted_at', '=', null)->where('mta.deleted_at', '=', null)
			->select('mta.*', 'c.number', 'c.firstname', 'c.lastname')
			->get();

		foreach ($mtas as $mta)
			$ret[$mta->id] = $mta->hostname.' ('.$mta->mac.") ⇒ ".$mta->number.": ".$mta->lastname.", ".$mta->firstname;

		return $ret;
	}



	/**
	 * Checks if a number can be reassigned to a given new modem
	 *
	 * @author Patrick Reichel
	 */
	protected function _phonenumber_reassignment_allowed($cur_modem, $new_modem) {

		// check if modems belong to the same contract
		if ($cur_modem->contract->id != $new_modem->contract->id) {
			return False;
		}

		// check if installation addresses are equal
		if (
			($cur_modem->salutation != $new_modem->salutation)
			||
			($cur_modem->company != $new_modem->company)
			||
			($cur_modem->department != $new_modem->department)
			||
			($cur_modem->firstname != $new_modem->firstname)
			||
			($cur_modem->lastname != $new_modem->lastname)
			||
			($cur_modem->street != $new_modem->street)
			||
			($cur_modem->house_number != $new_modem->house_number)
			||
			($cur_modem->zip != $new_modem->zip)
			||
			($cur_modem->city != $new_modem->city)
			||
			($cur_modem->district != $new_modem->district)
			||
			($cur_modem->installation_address_change_date != $new_modem->installation_address_change_date)
		) {
			return False;
		}

		// all checks passed: reassignment is allowed
		return True;
	}


	/**
	 * Return a list of MTAs the current phonenumber can be assigned to.
	 *
	 * @author Patrick Reichel
	 */
	public function mtas_list_phonenumber_can_be_reassigned_to() {

		// special case activated envia TEL module:
		//   - MTA has to belong to the same contract
		//   - Installation address of current modem match installation address of new modem
		if (\PPModule::is_active('provvoipenvia')) {
			$ret = array();

			$cur_modem = $this->mta->modem;
			$candidate_modems = $cur_modem->contract->modems;
			foreach ($candidate_modems as $tmp_modem) {

				if ($this->_phonenumber_reassignment_allowed($cur_modem, $tmp_modem)) {

					foreach ($tmp_modem->mtas as $mta) {
						$ret[$mta->id] = $mta->hostname.' ('.$mta->mac.")";
					}
				}
			}

			return $ret;
		}

		// default: can use every mta assigned to a contract
		return $this->mtas_list_only_contract_assigned();
	}


	/**
	 * link to management
	 */
	public function phonenumbermanagement() {
		return $this->hasOne('Modules\ProvVoip\Entities\PhonenumberManagement');
	}

	/**
	 * Phonenumbers can be related to EnviaOrders – if this module is active.
	 *
	 * @param	$withTrashed boolean; if true return also soft deleted orders; default is false
	 * @param	$whereStatement raw SQL query; default is returning of all orders
	 *				Attention: Syntax of given string has to meet SQL syntax!
	 * @return	EnviaOrders if module ProvVoipEnvia is enabled, else “null”
	 *
	 * @author Patrick Reichel
	 */
	public function enviaorders($withTrashed=False, $whereStatement="1") {

		if (!\PPModule::is_active('provvoipenvia')) {
			return null;
		}

		if ($withTrashed) {
			$orders = $this->belongsToMany('Modules\ProvVoipEnvia\Entities\EnviaOrder', 'enviaorder_phonenumber', 'phonenumber_id', 'enviaorder_id')->withTrashed()->whereRaw($whereStatement)->withTimestamps();
		}
		else {
			$orders = $this->belongsToMany('Modules\ProvVoipEnvia\Entities\EnviaOrder', 'enviaorder_phonenumber', 'phonenumber_id', 'enviaorder_id')->whereRaw($whereStatement)->withTimestamps();
			/* $orders = $this->belongsToMany('Modules\ProvVoipEnvia\Entities\EnviaOrder', 'enviaorder_phonenumber', 'phonenumber_id', 'enviaorder_id'); */
		}

		return $orders;
	}


	/**
	 * Helper to detect if an envia TEL contract has been created for this phonenumber
	 * You can either make a bool test against this method or get the id of a contract has been created
	 *
	 * @return misc:
	 *			null if module ProvVoipEnvia is disabled
	 *			false if there is no envia TEL contract
	 *			external_contract_id for the contract the number belongs to
	 *
	 *
	 * @author Patrick Reichel
	 */
	public function envia_contract_created() {

		// no envia module ⇒ no envia contracts
		if (!\PPModule::is_active('provvoipenvia')) {
			return null;
		}

		// the check is simple: if there is an external contract ID we can be sure that a contract has been created
		if (!is_null($this->contract_external_id)) {
			return $this->contract_external_id;
		}
		else {
			return false;
		}
	}


	/**
	 * Helper to detect if an envia TEL contract has been terminated for this phonenumber.
	 * You can either make a bool test against this method or get the id of a contract if terminated
	 *
	 * @return misc:
	 *			null if module ProvVoipEnvia is disabled
	 *			false if there is no envia TEL contract or the contract is still active
	 *			external_contract_id for the contract if terminated
	 *
	 * @author Patrick Reichel
	 */
	public function envia_contract_terminated() {

		// no envia module ⇒ no envia contracts
		if (!\PPModule::is_active('provvoipenvia')) {
			return null;
		}

		// if there is no external id we assume that there is no envia contract
		if (is_null($this->contract_external_id)) {
			return false;
		}

		// as we are able to delete single phonenumbers from a contract (without deleting the contract if other numbers are attached)
		// we here have to count the numbers containing the current external contract id

		$envia_contract = \Modules\ProvVoipEnvia\Entities\EnviaContract::where('envia_contract_reference', '=', $this->contract_external_id)->first();

		// no contract – seems to be deleted
		if (is_null($envia_contract)) {
			return $envia_contract;
		}

		// no end date set: contract seems to be active
		if (is_null($envia_contract->external_termination_date) && is_null($envia_contract->end_date)) {
			return false;
		}

		return $this->contract_external_id;
	}


	/**
	 * link to monitoring
	 *
	 * @author Ole Ernst
	 */
	public function cdrs()
	{
		return $this->hasMany('Modules\VoipMon\Entities\Cdr');
	}

	/**
	 * Daily conversion (called by cron job)
	 *
	 * @author Patrick Reichel
	 */
	public function daily_conversion() {

		$this->set_active_state();
	}


	/**
	 * (De)Activate phonenumber depending on existance and (de)activation dates in PhonenumberManagement
	 *
	 * @author Patrick Reichel
	 */
	public function set_active_state() {

		$changed = False;

		$management = $this->phonenumbermanagement;

		if (is_null($management)) {

			// if there is still no management: deactivate the number
			// TODO: decide if a phonenumbermanagement is required in each case or not
			// until then: don't change the state on missing management
			/* if ($this->active) { */
			/* 	$this->active = False; */
			/* 	$changed = True; */
			/* } */
			\Log::info('No PhonenumberManagement for phonenumber '.$this->prefix_number.'/'.$this->number.' (ID '.$this->id.') – will not change the active state.');
		}
		else {

			// get the dates for this number
			$act = $management->activation_date;
			$deact = $management->deactivation_date;

			if (!boolval($act)) {

				// Activation date not yet reached: deactivate
				if ($this->active) {
					$this->active = False;
					$changed = True;
				}
			}
			elseif ($act > date('c')) {

				// Activation date not yet reached: deactivate
				if ($this->active) {
					$this->active = False;
					$changed = True;
				}
			}
			else {
				if (!boolval($deact)) {

					// activation date today or in the past, no deactivation date: activate
					if (!$this->active) {
						$this->active = True;
						$changed = True;
					}
				}
				else {
					if ($deact > date('c')) {

						// activation date today or in the past, deactivation date in the future: activate
						if (!$this->active) {
							$this->active = True;
							$changed = True;
						}
					}
					else {

						// deactivation date today or in the past: deactivate
						if ($this->active) {
							$this->active = False;
							$changed = True;
						}
					}
				}
			}
		}
		// write to database if there are changes
		if ($changed) {
			if ($this->active) {
				\Log::info('Activating phonenumber '.$this->prefix_number.'/'.$this->number.' (ID '.$this->id.').');
			}
			else {
				\Log::info('Deactivating phonenumber '.$this->prefix_number.'/'.$this->number.' (ID '.$this->id.').');
			}

			$this->save();
		};

	}


	/**
	 * Dummy method to match BaseModel::delete() requirements
	 *
	 * We do not have to delete envia TEL orders here – this is later done by cron job.
	 *
	 * @author Patrick Reichel
	 */
	public function deleteNtoMEnviaOrder($envia_order) {

		return $envia_order->delete();
	}


	/**
	 * BOOT:
	 * - init phone observer
	 */
	public static function boot()
	{
		parent::boot();

		Phonenumber::observe(new PhonenumberObserver);
	}
}


/**
 * Phonenumber Observer Class
 * Handles changes on Phonenumbers
 *
 * can handle   'creating', 'created', 'updating', 'updated',
 *              'deleting', 'deleted', 'saving', 'saved',
 *              'restoring', 'restored',
 *
 * @author Patrick Reichel
 */
class PhonenumberObserver
{

	/**
	 * For envia TEL API we create username and login if not given.
	 * Otherwise envia TEL will do this – so we would have to ask for this data…
	 *
	 * @author Patrick Reichel
	 */
	protected function _create_login_data($phonenumber) {

		if (\PPModule::is_active('provvoipenvia') && ($phonenumber->mta->type == 'sip')) {

			if (!boolval($phonenumber->password)) {
				$phonenumber->password = \Acme\php\Password::generate_password(15, 'envia');
			}

			// username at envia TEL defaults to prefixnumber + number – we also do so
			if (!boolval($phonenumber->username)) {
				$phonenumber->username = $phonenumber->prefix_number.$phonenumber->number;
			}

		}
	}


	public function creating($phonenumber) {

		// TODO: ATM we don't force the creation of phonenumbermanagements – if we change our mind we can activate this line again
		// on creating there can not be a phonenumbermanagement – so we can set active state to false in each case
		// $phonenumber->active = 0;

		$this->_create_login_data($phonenumber);
	}


	public function created($phonenumber)
	{
		$phonenumber->mta->make_configfile();
		$phonenumber->mta->restart();

	}


	/**
	 * Checks if updating the phonenumber is allowed.
	 * Used to prevent problems related with envia TEL.
	 *
	 * @author Patrick Reichel
	 */
	protected function _updating_allowed($phonenumber) {

		// no envia TEL => no problems
		if (!\PPModule::is_active('provvoipenvia')) {
			return true;
		}

		// else we have to check if both MTAs belong to the same contract and if both modem's installation addresses are the same
		$new_mta = $phonenumber->mta;
		$old_mta = MTA::findOrFail(intval($phonenumber->getOriginal()['mta_id']));

		// if MTA has not changed: no problems
		if ($new_mta->id == $old_mta->id) {
			return true;
		}

		if (!$this->_phonenumber_reassignment_allowed($old_mta->modem, $new_mta->modem)) {
			\Session::push('tmp_error_above_form', "Reassignement of phonenumber to MTA $new_mta->id not allowed");
			return False;
		}

		return true;
	}

	public function updating($phonenumber) {

		if (!$this->_updating_allowed($phonenumber)) {
			return false;
		}

		$this->_create_login_data($phonenumber);
	}


	public function updated($phonenumber)
	{
		// uncommented by nino: redundant and senseless here
		// $this->_create_login_data($phonenumber);

		// check if we have a MTA change
		$this->_check_and_process_mta_change($phonenumber);

		// changes on SIP data (username, password, sipdomain) have to be sent to external providers, too
		$this->_check_and_process_sip_data_change($phonenumber);

		// rebuild the current mta's configfile and restart the modem – has to be done in each case
		$phonenumber->mta->make_configfile();
		$phonenumber->mta->restart();
	}


	/**
	 * Apply changes on assigning a phonenumber to a new MTA.
	 *
	 * @author Patrick Reichel
	 */
	protected function _check_and_process_mta_change($phonenumber) {

		$old_mta_id = intval($phonenumber['original']['mta_id']);
		$new_mta_id = intval($phonenumber->mta_id);

		// if the MTA has not been changed we have nothing to do :-)
		if ($old_mta_id == $new_mta_id) {
			return;
		}

		// get an instance of both MTAs for easier access
		$old_mta = MTA::findOrFail($old_mta_id);
		$new_mta = $phonenumber->mta;

		// rebuild old MTA's config and restart the modem (we have to remove all information about this phonenumber)
		$old_mta->make_configfile();
		$old_mta->restart();

		// for all possible external providers we have to check if there is data to update, too
		$this->_check_and_process_mta_change_for_envia($phonenumber, $old_mta, $new_mta);

	}

	/**
	 * Change envia TEL related data on assigning a phonenumber to a new MTA.
	 * Here we have to decide if the change is permanent (customer got new modem) or temporary (e.g. for testing reasons).
	 *
	 * @author Patrick Reichel
	 */
	protected function _check_and_process_mta_change_for_envia($phonenumber, $old_mta, $new_mta) {

		// check if module is enabled
		if (!\PPModule::is_active('provvoipenvia')) {
			return;
		}

		// we need some helpers for easier access
		$old_modem = $old_mta->modem;
		$old_contract = $old_modem->contract;
		$new_modem = $new_mta->modem;
		$new_contract = $new_modem->contract;

		// if the phonenumber does not exist at envia TEL (no management or no external creation date):
		// nothing to cange in modems
		if (
			(!$phonenumber->contract_external_id)
		) {
			\Session::push('tmp_info_above_form', 'Number has not been created at envia TEL – will not change any modem data.');
			return;
		};

		// the moment we get here we take for sure that we have a permanent switch (defective old modem)
		// now we have to do a bunch of envia TEL data related work

		// first: get all the orders related to the number or the old modem
		// and overwrite the modem_id with the new modem's id
		$phonenumber_related_orders = $phonenumber->enviaorders(true)->get();
		$contract_related_orders = \Modules\ProvVoipEnvia\Entities\EnviaOrder::withTrashed()->where('modem_id', $old_modem->id)->get();

		// build a collection of all orders that need to be changed
		// this are all orders related to the current phonenumber or related to contract but not related to phonenumber (e.g. orders that created other phonenumbers)
		$related_orders = $phonenumber_related_orders;
		while ($tmp_order = $contract_related_orders->pop()) {
			$related_numbers = $tmp_order->phonenumbers;
			if ($related_numbers->isEmpty() || $related_numbers->contains($phonenumber)) {
				$related_orders->push($tmp_order);
			}
		}
		$related_orders = $related_orders->unique();

		// change the modem id to the value of the new modem
		foreach ($related_orders as $order) {
			$order->modem_id = $new_modem->id;
			$order->save();
		}

		// second: write all envia TEL related data from the old to the new modem
		if (!$new_modem->contract_ext_creation_date) {
			$new_modem->contract_ext_creation_date = $old_modem->contract_ext_creation_date;
		}
		else {
			$new_modem->contract_ext_creation_date = min($new_modem->contract_ext_creation_date, $old_modem->contract_ext_creation_date);
		}
		if (!$new_modem->contract_ext_termination_date) {
			$new_modem->contract_ext_termination_date = $old_modem->contract_ext_termination_date;
		}
		else {
			$new_modem->contract_ext_termination_date = max($new_modem->contract_ext_termination_date, $old_modem->contract_ext_termination_date);
		}
		$new_modem->save();

		// third: if there are no more numbers attached to the old modem: remove all envia TEL related data
		if (!$old_modem->has_phonenumbers_attached()) {
			$old_modem->remove_envia_related_data();
		}
		else {
			$attributes = ['target'=>'_blank'];

			// prepare the link (for view) for old modem (this may be useful as we get the breadcrumb for the new modem on our return to phonenumber.edit)
			$parameters = [
				'modem' => $old_modem->id,
			];
			$title = 'modem '.$old_modem->id. ' ('.$old_modem->mac.')';
			$modem_href = \HTML::linkRoute('Modem.edit', $title, $parameters, $attributes);

			// prepare the links to the phonenumbers still related to old modem (they probably also have to be moved to another MTA)
			$numbers = [];
			foreach ($old_modem->mtas as $tmp_mta) {
				foreach ($tmp_mta->phonenumbers->all() as $tmp_phonenumber) {
					$tmp_parameters = [
						'phonenumber' => $tmp_phonenumber->id,
					];
					$tmp_title = $tmp_phonenumber->prefix_number.'/'.$tmp_phonenumber->number;
					$tmp_href = \HTML::linkRoute('Phonenumber.edit', $tmp_title, $tmp_parameters, $attributes);
					array_push($numbers, $tmp_href);
				}
			}
			$numbers = '<br>&nbsp;&nbsp;'.implode('<br>&nbsp;&nbsp;', $numbers);

			\Session::push('tmp_info_above_form', "There are still phonenumbers attached to ".$modem_href."! Don't forget to move them, too:".$numbers);
		}

	}


	/**
	 * If SIP data has been changed there are probably changes at your provider needed!
	 *
	 * @author Patrick Reichel
	 */
	protected function _check_and_process_sip_data_change($phonenumber) {

		if (
			($phonenumber['original']['username'] != $phonenumber->username)
			||
			($phonenumber['original']['password'] != $phonenumber->password)
			||
			($phonenumber['original']['sipdomain'] != $phonenumber->sipdomain)
		) {
			$this->_check_and_process_sip_data_change_for_envia($phonenumber);
		}

	}


	/**
	 * If SIP data has been changed and module ProvVoipEnvia is enabled:
	 * Change this data at envia TEL, too
	 *
	 * @author Patrick Reichel
	 */
	protected function _check_and_process_sip_data_change_for_envia($phonenumber) {

		// check if module is enabled
		if (!\PPModule::is_active('provvoipenvia')) {
			return;
		}

		// check what changed the SIP data
		if (
			(strpos(\URL::current(), "request/contract_get_voice_data") !== false)
			||
			(strpos(\URL::current(), "cron/contract_get_voice_data") !== false)
		) {
			// changed through API method get_voice_data: do nothing
			return;
		}
		else {
			// if we end up here: the current change has been done manually
			// inform the user that he has to change the data at envia TEL, too
			// TODO: check if this data can be changed automagically at envia TEL!
			$parameters = [
				'job' => 'voip_account_update',
				'origin' => urlencode(\URL::previous()),
				'phonenumber_id' => $phonenumber->id,
				];

			$title = 'DO THIS MANUALLY NOW!';
			$envia_href = \HTML::linkRoute('ProvVoipEnvia.request', $title, $parameters);

			\Session::push('tmp_info_above_form', 'Autochanging of SIP data at envia TEL is not implemented yet.<br>You have to '.$envia_href);
		}
	}


	public function deleted($phonenumber)
	{
		$phonenumber->mta->make_configfile();
		$phonenumber->mta->restart();

		// check if this number has been the last on old modem ⇒ if so remove envia related data from modem
		if (!$phonenumber->mta->modem->has_phonenumbers_attached()) {
			$phonenumber->mta->modem->remove_envia_related_data();
		}
	}
}
