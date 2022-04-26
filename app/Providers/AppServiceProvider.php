<?php

namespace App\Providers;

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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->instance('path.lang', $this->app->basePath() . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'lang');

        config(['services.facebook.client_id' => setting('facebook_app_id')]);
        config(['services.facebook.client_secret' => setting('facebook_app_secret')]);
        config(['services.facebook.redirect' => url('auth/facebook/callback')]);

        config(['services.google.client_id' => setting('google_app_id')]);
        config(['services.google.client_secret' => setting('google_app_secret')]);
        config(['services.google.redirect' => url('auth/google/callback')]);

        config(['app.search_tags_key' => "#"]);
        config(['app.languages' => [
            'en' => 'English',
            'ar' => 'Arabic',
            'fr' => 'Franch'
        ]]);
    }
}
