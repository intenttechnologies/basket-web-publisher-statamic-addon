<?php

namespace Basket\AddToBasket;

use Statamic\Statamic;
use Statamic\Providers\AddonServiceProvider;

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
            'resources/js/add_to_basket.js',
        ],
        'publicDirectory' => 'resources/dist',
    ];

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
    }

    protected function bootConfig()
    {
        $directory = $this->getAddon()->directory();
        $slug = $this->getAddon()->slug();

        $this->publishTemplate($slug, $directory);
        $this->publishConfig($slug, $directory);

        return $this;
    }

    protected function publishTemplate($slug, $directory)
    {
        $filename = "add-to-basket-button.blade.php";
        $origin = "{$directory}resources/templates/{$filename}";

        $this->publishes([
            $origin => resource_path("views/vendor/basket/{$filename}"),
        ], "{$slug}-template");
    }

    protected function publishConfig($slug, $directory)
    {
        $origin = "{$directory}config/{$slug}.php";

        $this->publishes([
            $origin => config_path("{$slug}.php"),
        ], "{$slug}-config");
    }
}
