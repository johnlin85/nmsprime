<?php

namespace App\Http\Controllers;

use Str;
use Module;
use Bouncer;

class ModulesController extends BaseController
{
    public function install($module)
    {
        // TODO: like in install_or_remove()
        d('yum install '.$module);

        $this->yum_log();
    }

    public function remove($module)
    {
        // TODO: like in install_or_remove()
        d('yum remove '.$module);

        $this->yum_log();
    }

    public function install_or_remove()
    {
        $shell = '';

        foreach (\Input::get() as $package => $todo) {
            if ($todo == 'install') {
                $shell .= "\n install ".$package;
            }
            if ($todo == 'remove') {
                $shell .= "\n remove ".$package;
            }
        }

        $shell .= "\n run";

        \File::put('/var/www/nmsprime/storage/systemd/installd', $shell);

        $this->yum_log();
    }

    public function yum_log()
    {
        d("TODO: parse /tmp/yum.log with realtime reload on Modules index page");
    }

    /**
     * Modules List Page for Install / Uninstall
     *
     * @author Torsten Schmidt
     */
    public function index()
    {
        $tmp = get_parent_class();
        $base_controller = new $tmp;

        $file = "data/dashboard/modules.json";

        if (! \Storage::exists($file)) {
            \Modules\Dashboard\Http\Controllers\DashboardController::newsLoadToFile();

            if (! \Storage::exists($file)) {
                return;
            }
        }

        $modules = json_decode(\Storage::get($file), true);

        $installed = \Module::enabled();

        foreach ($installed as $name => $install) {
            $modules[$name]['installed'] = true;
        }

        // d($modules, $installed);

        return \View::make('GlobalConfig.modules', $base_controller->compact_prep_view(compact('modules')));
    }

    // helper for creating https://support.nmsprime.com/modules.json array
    public function create_modules_json_file()
    {
        foreach ($modules as $module) {
            $a[$module->name] = ['icon' => $module->icon, 'package' => 'nmsprime-'.strtolower($module->name)];
        }

        $json = json_encode($a);

        \File::put(storage_path('nmsprime-modules.json'),$json);
    }
}
