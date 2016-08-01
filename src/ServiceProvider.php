<?php

namespace TomSchlick\ServerPush;

use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

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
        $instance = app('server-push');

        foreach (config('server-push.default_links', []) as $type => $paths) {
            $type = rtrim($type, 's');
            foreach ($paths as $path) {
                $instance->queueResource($path, $type);
            }
        }
    }

    /**
     * Parse and load links from an existing elixir revision manifest file.
     *
     * @return void
     */
    protected function registerElixirLinks()
    {
        $instance = app('server-push');

        if (config('server-push.autolink_elixir')) {
            $revPath = public_path().'/build/rev-manifest.json';
            if (file_exists($revPath)) {
                $revMap = json_decode(file_get_contents($revPath), true);
                if ($revMap) {
                    foreach (array_values($revMap) as $path) {
                        $instance->queueResource('/build/'.ltrim($path, '/'));
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
