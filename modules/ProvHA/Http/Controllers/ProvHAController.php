<?php

namespace Modules\ProvHA\Http\Controllers;

use View;
use App\Http\Controllers\BaseController;

class ProvHAController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $title = 'ProvHA Dashboard';

        return View::make('provha::index', $this->compact_prep_view(compact('title')));
    }


    /**
     * defines the formular fields for the edit and create view
     */
    public function view_form_fields($model = null)
    {
        // label has to be the same like column in sql table
        return [
            [
                'form_type' => 'text',
                'name' => 'master',
                'description' => trans('provha::view.master'),
                'help' => trans('provha::help.master', ['values' => implode('|', config('provha.hostname_and_ips'))])
            ],
            [
                'form_type' => 'text',
                'name' => 'slaves',
                'description' => trans('provha::view.slaves'),
                'help' => trans('provha::help.slaves')
            ],
            [
                'form_type' => 'text',
                'name' => 'load_ratio_master',
                'description' => trans('provha::view.load_ratio_master'),
                'help' => trans('provha::help.load_ratio_master')],
            [
                'form_type' => 'text',
                'name' => 'slave_config_rebuild_interval',
                'description' => trans('provha::view.slave_config_rebuild_interval'),
                'help' => trans('provha::help.slave_config_rebuild_interval')
            ],
        ];
    }
}
