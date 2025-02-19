<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        User::unguard();

        $this->app->singleton('storageData', function () {
            return collect(
                dataFromJson('Storages.json')
            );
        });

        $this->app->singleton('itemData', function () {
            return Cache::rememberForever('itemData', function () {
                return collect(dataFromJson('Items.json'))->sortBy(function ($item) {
                    return $item->id;
                });
            });
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::preventLazyLoading(! $this->app->environment('production'));
        Model::preventSilentlyDiscardingAttributes(! $this->app->environment('production'));

        if($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
    }
}
