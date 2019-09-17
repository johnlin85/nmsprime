<?php

namespace Modules\ProvHA\Entities;

class ProvHA extends \BaseModel
{
    // The associated SQL table for this Model
    protected $table = 'provha';

    // Add your validation rules here
    public static function rules($id = null)
    {
        return [
        ];
    }

    // Name of View
    public static function view_headline()
    {
        return 'ProvHA Config';
    }

    // link title in index view
    public function view_index_label()
    {
        return 'ProvHA';
    }

    // View Icon
    public static function view_icon()
    {
        return '<i class="fa fa-server"></i>';
    }

    public static function boot()
    {
        parent::boot();

        self::observe(new ProvHAObserver);
    }
}

class ProvHAObserver
{
}
