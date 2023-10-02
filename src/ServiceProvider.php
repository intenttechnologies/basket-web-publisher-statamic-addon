<?php

namespace Basket\AddToBasket;

use Statamic\Statamic;
use Statamic\Events\EntrySaving;
use Statamic\Providers\AddonServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use Basket\AddToBasket\BasketFieldtype;


class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        BasketTags::class,
    ];

    protected $fieldtypes = [
        AddToBasketFieldtype::class,
    ];

    protected $vite = [
        'input' => [
            'resources/js/cp.js',
            'resources/js/add_to_basket.js'
        ],
        'publicDirectory' => 'resources/dist',
    ];

    // protected $routes = [
    //     // for the control panel
    //     'cp' => __DIR__.'/../routes/cp.php',
    // ];

    public function bootAddon()
    {
        Statamic::provideToScript(
            [
                'add-to-basket:environment' => config('add-to-basket.environment'),
                'add-to-basket:api_key' => config('add-to-basket.api_key'),
                'add-to-basket:publisher_name' => config('add-to-basket.publisher_name'),
                'add-to-basket:enable_debug_log' => config('add-to-basket.enable_debug_log'),
            ]
        );

        // $this->registerActionRoutes(function () {
        //     Route::get('test', function (Request $request) {
        //         Log::debug(">>> action route");
        //         return response()->json(['result' => 'action:ok']); 
        //     });
        // });

        // $this->registerCpRoutes(function () {
        //     Route::get('test', function (Request $request) {
        //         Log::debug(">>> action route");
        //         return response()->json(['result' => 'cp:ok']); 
        //     });
        // });
    }

    protected function bootConfig()
    {
        $filename = $this->getAddon()->slug();
        $directory = $this->getAddon()->directory();
        $origin = "{$directory}config/{$filename}.php";

        if (!$this->config || !file_exists($origin)) {
            return $this;
        }

        $this->publishes([
            $origin => config_path("{$filename}.php"),
        ], "{$filename}-config");

        return $this;
    }
}
