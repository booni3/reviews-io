<?php

namespace Booni3\ReviewsIo;

use Illuminate\Support\ServiceProvider;

class ReviewsIoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/reviewsIo.php' => config_path('reviewsIo.php'),
        ], 'reviewsIo');
    }

    /**
     * Get the services provided by the provider.
     * This will defer loading of the service until it is requested.
     *
     * @return array
     */
    public function provides()
    {
        return [
            ReviewsIo::class
        ];
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom( __DIR__.'/../config/reviewsIo.php', 'reviewsIo');

        $this->app->singleton(ReviewsIo::class, function ($app) {
            $config = $app->make('config');

            $url = $config->get('reviewsIo.url');

            $store = $config->get('reviewsIo.store_id');

            $api = $config->get('reviewsIo.api_key');

            return new ReviewsIo($url, $store, $api);
        });

        $this->app->alias(ReviewsIo::class, 'reviewsId');
    }

}
