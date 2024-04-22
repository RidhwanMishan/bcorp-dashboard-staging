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
        //
//        $this->app['request']->server->set('HTTPS', true);
        //bepa 2020-08-20
        /*
        error_log(\URL::current());
        if (\URL::current() == 'http://localhost:8000/common/oauth2/v2.0/authorize' || \URL::current() == 'https://login.microsoftonline.com') {
            error_log('microsoft');
            \URL::forceRootUrl('https://login.microsoftonline.com');
        } else {
            error_log('local');
            \URL::forceRootUrl('https://webapptest.central-sugars.com.my');
        }
        */

        // \URL::forceRootUrl('https://webapptest.central-sugars.com.my');
        // \URL::forceScheme('https');
    }
}
