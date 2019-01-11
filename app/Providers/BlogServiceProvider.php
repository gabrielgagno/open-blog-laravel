<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\ResponseBuilder;

class BlogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ResponseBuilder::class, function($app) {
            return new ResponseBuilder();
        });
    }
}
