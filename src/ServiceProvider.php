<?php

namespace TomSchlick\ServerPush;

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
        $this->publishes([
            __DIR__ . '/../config/server-push.php' => config_path('server-push.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('server-push', function () {
            return $this->app->make(HttpPush::class);
        });

        $this->registerDefaultLinks();
        $this->registerElixirLinks();
    }

    /**
     * Load the default links from the config.
     */
    protected function registerDefaultLinks()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/server-push.php', 'server-push');
        $instance = app('server-push');

        foreach (config('server-push.default_links', []) as $type => $paths) {
            $type = rtrim($type, 's');
            foreach ($paths as $path) {
                $instance->queueResource($path, $type);
            }
        }
    }

    protected function registerElixirLinks()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/server-push.php', 'server-push');
        $instance = app('server-push');

        if (config('server-push.autolink_elixir')) {
            $revPath = public_path() . '/build/rev-manifest.json';
            if (file_exists($revPath)) {
                $revMap = json_decode($revPath, true);
                foreach (array_values($revMap) as $path) {
                    $instance->queueResource('/' . ltrim($path, '/'));
                }
            }
        }
    }
}
