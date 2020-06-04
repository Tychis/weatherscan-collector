<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\{AlertHistoryInterface, AlertHistoryRepository, AlertTypeInterface, AlertTypeRepository, ATOMUrlsInterface, ATOMUrlsRepository, CurrentConditionsInterface, CurrentConditionsRepository, LocationInterface, LocationRepository, CountyInterface, CountyRepository};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\GuzzleHttp\Client::class, function ($app) {
            $request_client = new \GuzzleHttp\Client;
            return $request_client;
        });
        $this->app->singleton(AlertHistoryInterface::class, AlertHistoryRepository::class);
        $this->app->singleton(AlertTypeInterface::class, AlertTypeRepository::class);
        $this->app->singleton(ATOMUrlsInterface::class, ATOMUrlsRepository::class);
        $this->app->singleton(CurrentConditionsInterface::class, CurrentConditionsRepository::class);
        $this->app->singleton(LocationInterface::class, LocationRepository::class);
        $this->app->singleton(CountyInterface::class, CountyRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
