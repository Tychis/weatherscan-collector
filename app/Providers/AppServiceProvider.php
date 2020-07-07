<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\{AlertHistoryInterface, AlertHistoryRepository, AlertTypeInterface, AlertTypeRepository, ATOMUrlsInterface, ATOMUrlsRepository, CurrentConditionsInterface, CurrentConditionsRepository, LocationInterface, LocationRepository, CountyInterface, CountyRepository};
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->bind(\GuzzleHttp\Client::class, function ($app) {
          $request_client = new \GuzzleHttp\Client;
          return $request_client;
      });
      $this->app->bind(AlertHistoryInterface::class, AlertHistoryRepository::class);
      $this->app->bind(AlertTypeInterface::class, AlertTypeRepository::class);
      $this->app->bind(ATOMUrlsInterface::class, ATOMUrlsRepository::class);
      $this->app->bind(CurrentConditionsInterface::class, CurrentConditionsRepository::class);
      $this->app->bind(LocationInterface::class, LocationRepository::class);
      $this->app->bind(CountyInterface::class, CountyRepository::class);
    }
}
