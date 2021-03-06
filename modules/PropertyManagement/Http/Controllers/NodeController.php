<?php

namespace Modules\PropertyManagement\Http\Controllers;

use App\Http\Controllers\BaseViewController;

class NodeController extends \BaseController
{
    /**
     * Defines the formular fields for the edit and create view
     */
    public function view_form_fields($model = null)
    {
        // label has to be the same like column in sql table
        $fields = [
            ['form_type' => 'text', 'name' => 'name', 'description' => 'Name', 'space' => 1],

            ['form_type' => 'text', 'name' => 'street', 'description' => 'Street', 'autocomplete' => []],
            ['form_type' => 'text', 'name' => 'house_nr', 'description' => 'House number'],
            ['form_type' => 'text', 'name' => 'zip', 'description' => 'Zip', 'autocomplete' => []],
            ['form_type' => 'text', 'name' => 'city', 'description' => 'City', 'autocomplete' => []],
            ['form_type' => 'text', 'name' => 'district', 'description' => 'District', 'autocomplete' => []],
            ['form_type' => 'text', 'name' => 'country_code', 'description' => 'Country code', 'help' => trans('helper.countryCode')],
            ['form_type' => 'html', 'name' => 'geopos', 'description' => trans('messages.geopos_x_y'), 'html' => BaseViewController::geoPosFields($model)],
            ['form_type' => 'text', 'name' => 'geocode_source', 'description' => 'Geocode origin', 'help' => trans('helper.Modem_GeocodeOrigin'), 'space' => 1],

            ['form_type' => 'text', 'name' => 'type', 'description' => 'Type of signal', 'autocomplete' => []],
            ['form_type' => 'checkbox', 'name' => 'headend', 'description' => 'Headend'],
        ];

        if (\Module::collections()->has('HfcReq')) {
            $netelement = new \Modules\HfcReq\Entities\NetElement;
            $netelements = $netelement->getParentList();

            $fields[] = ['form_type' => 'select', 'name' => 'netelement_id', 'description' => 'NetElement', 'value' => $netelements];
        }

        $fields[] = ['form_type' => 'textarea', 'name' => 'description', 'description' => 'Description'];

        return $fields;
    }
}
