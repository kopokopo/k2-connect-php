<?php


namespace Kopokopo\SDK\Providers;


use Illuminate\Support\ServiceProvider;
use Kopokopo\SDK\K2;

class KopoKopoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('Kopokopo', function($app)
        {
            $uri = "https://api.kopokopo.com";

            if (env('KOPOKOPO_ENV') == 'sandbox')
            {
                $uri = "https://sandbox.kopokopo.com";
            }

            return new K2([
                'clientId' => env('KOPOKOPO_CLIENT_ID'),
                'clientSecret' => env('KOPOKOPO_CLIENT_SECRET'),
                'apiKey' => env('KOPOKOPO_API_KEY'),
                'baseUrl' => $uri
            ]);
        });
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
