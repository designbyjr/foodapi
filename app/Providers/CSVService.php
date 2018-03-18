<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Csv;

class CSVService extends ServiceProvider
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
        $this->app->bind('Services\Csv', function ($app) {
          return new Services\Csv();
        });
    }
}
