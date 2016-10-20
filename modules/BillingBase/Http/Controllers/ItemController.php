<?php
namespace Modules\Billingbase\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Modules\BillingBase\Entities\Product;
use Modules\BillingBase\Entities\CostCenter;
use Modules\BillingBase\Entities\BillingBase;
use Modules\ProvBase\Entities\Contract;
use Config;

class ItemController extends \BaseController {

	/**
	 * defines the formular fields for the edit and create view
	 */
	public function view_form_fields($model = null)
	{
		if (!$model)
			$model = new Item;

		$products = Product::select('id', 'type', 'name')->orderBy('type')->orderBy('name')->get()->all();

		// $prods = $model->html_list($products, 'name');
		$prods[0] = '';
		foreach ($products as $p)
			$prods[$p->id] = $p->type.' - '.$p->name;

		foreach ($products as $p)
			$types[$p->id] = $p->type;

		// the options should start with a 0 entry which is chosen if nothing is given explicitely
		// (watch $this->prepare_rules())
		// don't use array_merge for this because that reassignes the index!
		$ccs = $this->_add_empty_first_element_to_options($model->html_list(CostCenter::all(), 'name'));

		$cnt[0] = null;
		// 	$b[date('Y-m-01', strtotime("now +$i months"))] = date('Y-m', strtotime("now +$i months"));
		for ($i=1; $i < 10; $i++)
			$cnt[$i] = $i;


		// label has to be the same like column in sql table
		return array(
			array('form_type' => 'text', 'name' => 'contract_id', 'description' => 'Contract', 'value' => $model->contract(), 'hidden' => '1'),
			array('form_type' => 'select', 'name' => 'product_id', 'description' => 'Product', 'value' => $prods, 'select' => $types, 'help' => trans('helper.Item_ProductId')),
			array('form_type' => 'select', 'name' => 'count', 'description' => 'Count', 'value' => $cnt, 'select' => 'Device Other'),
			array('form_type' => 'text', 'name' => 'valid_from', 'description' => 'Valid from', 'options' => ['placeholder' => 'YYYY-MM-DD'], 'help' => trans('helper.Item_ValidFrom')),
			array('form_type' => 'checkbox', 'name' => 'valid_from_fixed', 'description' => 'Valid from fixed', 'select' => 'Internet Voip', 'help' => trans('helper.Item_ValidFromFixed')),
			array('form_type' => 'text', 'name' => 'valid_to', 'description' => 'Valid to', 'options' => ['placeholder' => 'YYYY-MM-DD']),
			array('form_type' => 'checkbox', 'name' => 'valid_to_fixed', 'description' => 'Valid to fixed', 'select' => 'Internet Voip', 'help' => trans('helper.Item_ValidToFixed')),
			array('form_type' => 'text', 'name' => 'credit_amount', 'description' => 'Credit Amount', 'select' => 'Credit', 'help' => trans('helper.Item_CreditAmount')),
			array('form_type' => 'select', 'name' => 'costcenter_id', 'description' => 'Cost Center (optional)', 'value' => $ccs),
			array('form_type' => 'text', 'name' => 'accounting_text', 'description' => 'Accounting Text (optional)')
		);
	}


	/**
	 * Set default Input values for empty fields / Autofill empty fields
	 */
	public function prepare_input($data)
	{
		$data['credit_amount'] = $data['credit_amount'] ? abs($data['credit_amount']) : $data['credit_amount'];
		$type = Product::findOrFail($data['product_id'])->type;

		// set default valid from date to tomorrow for this product types
		// specially for Voip: Has to be created externally – and this will not be done today…
		if ($type == 'Voip')
		{
			$data['valid_from'] = $data['valid_from'] ? : date('Y-m-d', strtotime('next day'));
		}

		// others: set today as start date when valid_from is fixed and not set, otherwise set to tomorrow - also if valid_from is in past
		elseif ($type == 'Internet')
		{
			if (isset($data['valid_from_fixed']) && boolval($data['valid_from_fixed']))
				$data['valid_from'] = $data['valid_from'] ? : date('Y-m-d');
			else
				$data['valid_from'] = $data['valid_from'] > date('Y-m-d') ? $data['valid_from'] : date('Y-m-d', strtotime('next day'));
		}

		else
		{
			$data['valid_from'] = $data['valid_from'] ? : date('Y-m-d');
			$data['valid_from_fixed'] = 1;
		}

		return parent::prepare_input($data);
	}


	/**
	 * @author Nino Ryschawy
	 */
	public function prepare_rules($rules, $data)
	{
		$rules['count'] = str_replace('product_id', $data['product_id'], $rules['count']);

		// termination only allowed on last day of month
		$fix = BillingBase::select('termination_fix')->first()->termination_fix;
		if ($fix && $data['valid_to'])
			$rules['valid_to'] .= '|In:'.date('Y-m-d', strtotime('last day of this month', strtotime($data['valid_to'])));

		// TODO: simplify when it's safe that valid_to=0000-00-00 can not occure
		if ($data['valid_to'] && $data['valid_to'] != '0000-00-00')
			$rules['valid_to'] .= '|after:'.$data['valid_from'];

		// new tariff start date must be after old tariff start date if valid tariff exists - otherwise after end date of old tariff - does it?
		$c = Contract::find($data['contract_id']);
		$p = Product::find($data['product_id']);
		$tariff = $c->get_valid_tariff($p->type);
		if ($tariff)
		{
			// check only on creating:
			if (\Str::contains(\URL::previous(), '/Item/create')) {
				// check if date is after today
				$start = $tariff->get_start_time();
				$rules['valid_from'] .= '|after:';
				$rules['valid_from'] .= $tariff->valid_to && $tariff->valid_to != '0000-00-00' ? $tariff->valid_to : date('Y-m-d', $start);
			}
		}

		// dd($rules, $data);

		return parent::prepare_rules($rules, $data);
	}

	public function index()
	{
		return \View::make('errors.generic');
	}
}
