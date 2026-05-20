<?php

namespace OnestoIt\Sdk;

use Illuminate\Support\ServiceProvider;

class OnestoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/onesto.php', 'onesto');

        $this->app->singleton('onesto', fn () => new Onesto());
        $this->app->alias('onesto', Onesto::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/onesto.php' => config_path('onesto.php'),
            ], 'onesto-config');
        }
    }
}
