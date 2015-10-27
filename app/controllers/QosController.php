<?php

use Models\Qos;

class QosController extends \BaseController {

    /**
     * defines the formular fields for the edit and create view
     */
	public function get_form_fields()
	{
		$qos = new Qos;

		// label has to be the same like column in sql table
		return array(
			array('form_type' => 'text', 'name' => 'name', 'description' => 'Name'),
			array('form_type' => 'text', 'name' => 'ds_rate_max', 'description' => 'DS Rate [MBit/s]'),
			array('form_type' => 'text', 'name' => 'us_rate_max', 'description' => 'US Rate [MBit/s]'),
		);
	}

}
