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
        $model->verify_settings();

        // label has to be the same like column in sql table
        return [
            [
                'form_type' => 'text',
                'name' => 'master',
                'description' => trans('provha::view.master'),
                'help' => trans('provha::help.master', ['values' => implode('|', config('provha.hostinfo.own_ips'))])
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
                'help' => trans('provha::help.load_ratio_master'),
                'options' => ['readonly'],
            ],
            [
                'form_type' => 'text',
                'name' => 'slave_config_rebuild_interval',
                'description' => trans('provha::view.slave_config_rebuild_interval'),
                'help' => trans('provha::help.slave_config_rebuild_interval')
            ],
        ];
    }


    /**
     * Modify form content
     *
     * @author Patrick Reichel
     */
    protected function prepare_input($data)
    {
        $data = parent::prepare_input($data);
        $data['slaves'] = str_replace(' ', '', $data['slaves']);

        // round up interval to full minute
        if (is_numeric($data['slave_config_rebuild_interval'])) {
            $data['slave_config_rebuild_interval'] = 60 * ceil($data['slave_config_rebuild_interval'] / 60);
        }

        return $data;
    }

    /**
     * Add list of this machine's hostname/IPs to check if master is in this list
     *
     * @author  Patrick Reichel
     */
    public function prepare_rules($rules, $data)
    {
        $this_machine = implode(',', config('provha.hostinfo.own_hostname_and_ips'));
        $rules = str_replace('placeholder__is_this_machine', "in:$this_machine", $rules);

        return parent::prepare_rules($rules, $data);
    }
}
