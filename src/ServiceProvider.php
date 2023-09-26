<?php

namespace Basket\AddToBasket;

use Statamic\Statamic;
use Statamic\Providers\AddonServiceProvider;
use Illuminate\Support\Facades\Log;
use Statamic\Events\EntrySaved;


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

    // protected $listen = [
    //     EntrySaved::class => [
    //         SaveBasketItems::class,
    //     ]
    // ];

    public function bootAddon()
    {
        Statamic::provideToScript(
            [
                'add-to-basket:environment' => config('add-to-basket.environment'),
                'add-to-basket:api_key' => config('add-to-basket.api_key'),
                'add-to-basket:publisher_name' => config('add-to-basket.publisher_name')
            ]
        );
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
