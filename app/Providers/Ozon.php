<?php

namespace App\Providers;


use App\Ozon\OzonApi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class Ozon extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * @return void
     */
    public function boot(): void
    {

//
//        Http::macro('sqwer', function () {
//            return Http::withHeaders([
//                'Content-Type' => 'application/json',
//                'Client-Id' => '576491',
//                'Api-Key' => 'fb7b2c33-eec2-4814-8951-3c665dd6d5fd',
//            ])->baseUrl('https://api-seller.ozon.ru/');
//        });

        $this->app->singleton(OzonApi::class, function ($app) {
            return new OzonApi([]);
        });

        // $api->post('/v3/product/info/stocks', ['filter' => ['visibility' =>  'ALL'], 'last_id' =>  '', 'limit' =>  100])
        // $api->productInfoStocksV3(['visibility' =>  'ALL'])
    }
}
