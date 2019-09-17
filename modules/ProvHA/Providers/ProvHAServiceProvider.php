<?php

namespace Modules\ProvHA\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class ProvHAServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->_add_ha_env_data();
    }


    /**
     * Add HA/LB related data of current host to super-global $_ENV
     *
     * @return void
     *
     * @author  Patrick Reichel
     */
    protected function _add_ha_env_data()
    {
        $hostname = gethostname();
        $ips_raw = trim(`hostname -I`);
        $ips = [];
        foreach (explode(' ', $ips_raw) as $ip) {
            $ips[] = trim($ip);
        }

        $_ENV['PROVHA__OWN_HOSTNAME'] = $hostname;
        $_ENV['PROVHA__OWN_IPS'] = $ips;
        $_ENV['PROVHA__OWN_HOSTNAME_AND_IPS'] = array_merge([$hostname], $ips);

        $provha_config = \DB::table('provha')->first();
        if (in_array($provha_config->master, $_ENV['PROVHA__OWN_HOSTNAME_AND_IPS'])) {
            $_ENV['PROVHA__OWN_STATE'] = 'master';
        } else {
            $_ENV['PROVHA__OWN_STATE'] = 'unknown';
            $slaves = explode(',', $provha_config->slaves);
            foreach ($slaves as $slave) {
                if (in_array(trim($slave), $_ENV['PROVHA__OWN_HOSTNAME_AND_IPS'])) {
                    $_ENV['PROVHA__OWN_STATE'] = 'slave';
                }
            }
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('provha.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'provha'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/provha');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/provha';
        }, \Config::get('view.paths')), [$sourcePath]), 'provha');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/provha');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'provha');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'provha');
        }
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
