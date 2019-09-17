<?php

namespace Modules\ProvHA\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Log;

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
     * Add HA/LB related data of current host to environment
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

        // extend env by own IPs and hostname
        putenv('PROVHA__OWN_HOSTNAME='.$hostname);
        putenv('PROVHA__OWN_IPS='.serialize($ips));
        putenv('PROVHA__OWN_HOSTNAME_AND_IPS='.serialize(array_merge([$hostname], $ips)));

        $provha_config = \DB::table('provha')->first();
        if (in_array($provha_config->master, unserialize(env('PROVHA__OWN_HOSTNAME_AND_IPS')))) {
            putenv('PROVHA__OWN_STATE_DETERMINED=master');
        } else {
            putenv('PROVHA__OWN_STATE_DETERMINED=unknown');
            $slaves = explode(',', $provha_config->slaves);
            foreach ($slaves as $slave) {
                if (in_array(trim($slave), unserialize(env('PROVHA__OWN_HOSTNAME_AND_IPS')))) {
                    putenv('PROVHA__OWN_STATE_DETERMINED=slave');
                }
            }
        }

        // check if own state is set in provha.env
        putenv('PROVHA__OWN_STATE='.strtolower(env('PROVHA__OWN_STATE', '')));
        if (! env('PROVHA__OWN_STATE')) {
            Log::critical('ProvHA: own state not set in provha.conf. Determined state is “'.env('PROVHA__OWN_STATE_DETERMINED').'”');
        } elseif (env('PROVHA__OWN_STATE') != env('PROVHA__OWN_STATE_DETERMINED')) {
            Log::critical('ProvHA: Configuration mismatch: .env configured host state (“'.env('PROVHA__OWN_STATE').'”) does not match determined host state (“'.env('PROVHA__OWN_STATE_DETERMINED').'”)');
        }
        else {
            Log::debug('ProvHA: Host state is “'.env('PROVHA__OWN_STATE').'”');
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
