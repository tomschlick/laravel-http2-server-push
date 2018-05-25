<?php

namespace TomSchlick\ServerPush;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/server-push.php');
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('server-push.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('server-push');
        }
        $this->mergeConfigFrom($source, 'server-push');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('server-push', function () {
            return new HttpPush();
        });

        $this->app->alias('server-push', HttpPush::class);

        $this->registerDefaultLinks();
        $this->registerElixirLinks();
    }

    /**
     * Load the default links from the config.
     *
     * @return void
     */
    protected function registerDefaultLinks()
    {
        app('server-push')->massAssign(config('server-push.default_links', []));
    }

    /**
     * Parse and load links from an existing elixir revision manifest file.
     *
     * @return void
     */
    protected function registerElixirLinks()
    {
        $instance = app('server-push');

        if (config('server-push.autolink_from_manifest')) {
            $revPath = config('server-push.manifest_path');
            $assetsBaseUri = config('server-push.assets_base_uri');
            if (file_exists($revPath)) {
                $revMap = json_decode(file_get_contents($revPath), true);
                if ($revMap) {
                    foreach (array_values($revMap) as $path) {
                        $assetUri = rtrim($assetsBaseUri, '/').'/'.ltrim($path, '/');
                        $instance->queueResource($assetUri);
                    }
                }
            }
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return ['server-push'];
    }
}
