<?php

namespace Modules\BillingBase\Entities;

use Modules\ProvBase\Entities\DocumentTemplate;

class Company extends \BaseModel
{
    // The associated SQL table for this Model
    public $table = 'company';

    // All HTML Input Fields that are discarded during Database Update
    public $guarded = ['logo_upload', 'conn_info_template_fn_upload'];

    // Add your validation rules here
    public static function rules($id = null)
    {
        return [
            // 'name' => 'required|unique:cmts,hostname,'.$id.',id,deleted_at,NULL'  	// unique: table, column, exception , (where clause)
            'name' 		=> 'required',
            'street' 	=> 'required',
            'zip'	 	=> 'required',
            'city'	 	=> 'required',
            'logo_upload' => 'mimes:jpg,jpeg,bmp,png,pdf',
            'conn_info_template_fn_upload' => 'mimetypes:text/x-tex,application/x-tex',
        ];
    }

    /**
     * View related stuff
     */

    // Name of View
    public static function view_headline()
    {
        return 'Company';
    }

    // View Icon
    public static function view_icon()
    {
        return '<i class="fa fa-industry"></i>';
    }

    // AJAX Index list function
    // generates datatable content and classes for model
    public function view_index_label()
    {
        $bsclass = $this->get_bsclass();

        return ['table' => $this->table,
                'index_header' => [$this->table.'.name', $this->table.'.city', $this->table.'.phone', $this->table.'.mail'],
                'bsclass' => $bsclass,
                'header' => $this->name,
                'order_by' => ['0' => 'asc'], // columnindex => direction
                ];
    }

    public function get_bsclass()
    {
        $bsclass = 'info';

        return $bsclass;
    }

    public function view_has_many()
    {
        $ret['Edit']['SepaAccount']['class'] = 'SepaAccount';
        $ret['Edit']['SepaAccount']['relation'] = $this->accounts;

        $ret['Edit']['DocumentTemplate']['class'] = 'DocumentTemplate';
        $ret['Edit']['DocumentTemplate']['relation'] = $this->documenttemplates;

        $ret['Edit']['DocumentTemplateDerived']['options']['hide_create_button'] = 1;
        $ret['Edit']['DocumentTemplateDerived']['options']['hide_delete_button'] = 1;
        $ret['Edit']['DocumentTemplateDerived']['view']['vars']['derived_documenttemplates'] = $this->get_derived_documenttemplates();
        $ret['Edit']['DocumentTemplateDerived']['view']['view'] = 'provbase::DocumentTemplate.relation_derived';

        return $ret;
    }

    /**
     * Relationships:
     */
    public function accounts()
    {
        return $this->hasMany('Modules\BillingBase\Entities\SepaAccount');
    }

    public function documenttemplates()
    {
        return $this->hasMany('Modules\ProvBase\Entities\DocumentTemplate')->where('sepaaccount_id', '=', null);
    }

    /**
     * Get all document templates that are used implicitely (“derived”).
     *
     * @return  \Collection  One template for each type if not defined here.
     *
     * @author  Patrick Reichel
     */
    public function derived_documenttemplates()
    {
        $templates = DocumentTemplate::leftJoin('documenttype', 'documenttype.id', '=', 'documenttemplate.documenttype_id')
            ->select('documenttemplate.*', 'documenttype.type_view')
            ->whereIn('module', array_keys(\Module::getByStatus(1)))
            ->where('sepaaccount_id', '=', null)
            ->where(function ($query) {
                $query->whereNull('company_id')
                      ->orWhere('company_id', '=', $this->id);
            })
            ->where('usable', '>', 0)
            ->orderBy('company_id')
            ->get();

        $types = [];
        foreach ($templates as $template) {
            if (is_null($template->company_id)) {
                $types[$template->documenttype_id] = $template;
            }
            elseif (array_key_exists($template->documenttype_id, $types)) {
                // this is save as we get the templates ordered by company_id
                unset($types[$template->documenttype_id]);
            }

        }
        return collect($types);
    }

    /**
     * Get all document templates that are used implicitely (“derived”) for use in relation blade.
     *
     * @author Patrick Reichel
     */
    public function get_derived_documenttemplates()
    {
        $derived_templates = $this->derived_documenttemplates();

        $ret = [];
        foreach ($derived_templates as $template) {
            $ret[$template->id] = $template->type_view;
        }

        asort($ret);
        return $ret;
    }

    /**
     * Returns data for use in controller edit selects.
     *
     * @author Patrick Reichel
     */
    public static function get_companies_for_edit_view($with_empty_first=false) {

        $ret = [];
        $companies = Company::all();
        foreach ($companies as $company) {
            $ret[$company->id] = $company->name.' ('.$company->city.')';
        }
        asort($ret);
        if ($with_empty_first) {
            $ret = [0 => '–'] + $ret;
        }
        return $ret;
    }

    /*
     * Init Observers
     */
    public static function boot()
    {
        self::observe(new CompanyObserver);
        parent::boot();
    }

    /**
     * Prepare data array with keys replaced by values in tex templates for pdf creation
     *
     * @return array
     *
     * @author Nino Ryschawy
     */
    public function template_data()
    {
        $class = 'company';
        $ignore = ['created_at', 'updated_at', 'deleted_at', 'id'];
        $data = [];

        foreach ($this->getAttributes() as $key => $value) {
            if (in_array($key, $ignore)) {
                continue;
            }

            // separate comma separated values by linebreakings
            if (in_array($key, ['management', 'directorate'])) {
                $value = explode(',', $value);
                $tmp = [];

                foreach ($value as $name) {
                    $tmp[] = trim(escape_latex_special_chars($name));
                }

                $data[$class.'_'.$key] = implode('\\\\', $tmp);

                continue;
            } elseif (! in_array($key, ['zip', 'conn_info_template_fn', 'logo'])) {
                $value = escape_latex_special_chars($value);
            }

            $data[$class.'_'.$key] = $value;
        }

        return $data;
    }
}

class CompanyObserver
{
    public function updated($company)
    {
        \Artisan::call('queue:restart');
    }
}
