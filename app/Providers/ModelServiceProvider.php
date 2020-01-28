<?php

namespace App\Providers;

use App\Traits\Helper;
use Illuminate\Support\ServiceProvider;

class ModelServiceProvider extends ServiceProvider
{

    use Helper;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach(config('app.services') as $service => $model) {
            $this->app->singleton($this->classBasename($service), function($app) use ($service, $model) {
                return new $service(new $model);
            });
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

}
