<?php

namespace Basket\AddToBasket;

use Statamic\Tags\Tags;
use Illuminate\View\View;

class BasketTags extends Tags
{
    protected static $handle = 'basket';
    protected $assetsFolder = 'vendor/add-to-basket/build/';

    public function scriptSrc(): string
    {
        $env = config('add-to-basket.environment');
        $replace = $env == "production" ? "www" : $env;
        $path = str_replace("%ENV%", $replace, config('add-to-basket.basket_path'));
        return $path . '/basketShare.js';
    }

    public function styleSrc(): string
    {
        $manifest = json_decode(file_get_contents($this->assetsFolder . 'manifest.json'), true);
        return "/". $this->assetsFolder . $manifest['resources/css/add-to-basket-button.css']['file'];
    }

    public function styleTag(): string
    {
        return '<link href="' . $this->styleSrc() . '" rel="stylesheet" />';
    }

    public function scriptTag(): string
    {
        return '<script defer src="' . $this->scriptSrc() . '"></script>';
    }

    public function shareModalOnclick(): string
    {
        $data = $this->params['data'];
        return $this->onClick($data);
    }

    private function onClick($data): string
    {
        $parsed = array_map(function ($item) {
            return [
                'url' => $item['url'],
                'id' => $item['id'],
                'originalUrl' => $item['originalUrl'],
                'cmsUrl' => isset($item['cmsUrl']) ? $item['cmsUrl'] : null,
            ];
        }, $data['items']);
        $name = config('add-to-basket.publisher_name');
        return '__BASKET__.shareModal({items:' . json_encode($parsed) . ', name:"' . $name . '"})';
    }

    public function shareModalButton(): View
    {
        $data = $this->params['data'];
        $onClick = $this->onClick($data);
        return view('vendor.basket.add-to-basket-button', ['onClick' => $onClick]);
    }
}
