<?php

namespace TomSchlick\ServerPush;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends BaseServiceProvider
{
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
    }
}
