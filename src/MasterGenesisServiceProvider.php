<?php

namespace Medigeneit\MasterGenesis;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MasterGenesisServiceProvider extends ServiceProvider
{
  public function register()
  {
    // Register dependencies
  }

  public function boot()
  {
    Route::middleware('api')->prefix('/api')->group(__DIR__ . '/../routes/api.php');

    // Load migrations
    $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

    // // Load resources
    // $this->loadViewsFrom(__DIR__ . '/../resources/views', 'master-genesis');

    // Publish the config file so users can customize it
    $this->publishes([
      __DIR__ . '/../config/master-genesis.php' => config_path('master-genesis.php'),
    ], 'config');
  }
}
