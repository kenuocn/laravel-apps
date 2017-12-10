<?php

namespace ElfSundae\Laravel\Apps;

use Illuminate\Support\ServiceProvider;

class AppsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        (new MacroRegistrar)->registerMacros($this->app);

        if ($this->app->runningInConsole()) {
            $this->publishAssets();
        }
    }

    /**
     * Publish assets from package.
     *
     * @return void
     */
    protected function publishAssets()
    {
        $this->publishes([
            __DIR__.'/../config/apps.php' => config_path('apps.php'),
        ], 'laravel-apps');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->setupAssets();

        $this->registerAppManager();
    }

    /**
     * Setup package assets.
     *
     * @return void
     */
    protected function setupAssets()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/apps.php', 'apps');
    }

    /**
     * Register app manager singleton.
     *
     * @return void
     */
    protected function registerAppManager()
    {
        $this->app->singleton('apps', function ($app) {
            return new AppManager($app);
        });

        $this->app->alias('apps', AppManager::class);
    }
}
