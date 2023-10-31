<?php

namespace Basket\AddToBasket;

use Statamic\Tags\Tags;
use Illuminate\Support\HtmlString;

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

    private function logo(): HtmlString
    {
        return new HtmlString(<<<HTML
            <svg class="basket-atb-button-logo" xmlns="http://www.w3.org/2000/svg" width="41" height="41" fill="currentColor" viewBox="0 0 41 41">
                <path d="M15.644.554c-9.83 0-14.7 9.32-14.7 20.1a58.49 58.49 0 0 0 2.23 15.11 3.81 3.81 0 0 0 2.56 2.57 59.347 59.347 0 0 0 15.21 2.22c10.28 0 20-4.56 20-14.7a13.4 13.4 0 0 0-12-13.24 13.53 13.53 0 0 0-13.3-12.06Zm8.48 12.48c-5.5 1.1-9 3.29-10.69 6.06a7 7 0 0 0-.05 7.79 6.39 6.39 0 0 0 7 2.28 9.79 9.79 0 0 0 5.8-4.91 19.825 19.825 0 0 0 1.94-4.95 20 20 0 0 0-5.46 1.06 7.68 7.68 0 0 1-2.68 3.69c-2.24 1.44-3.77-.28-2.46-2.38.85-1.35 3.27-3.3 9.25-4.15 5-.7 9.26 3.61 9.26 8.31 0 7.21-7 9.78-15.29 9.78a52.478 52.478 0 0 1-13.09-1.8 51.26 51.26 0 0 1-1.81-14.37c.24-7.21 3.17-14.16 10-14a8.67 8.67 0 0 1 8.28 7.59Z"/>
            </svg>
        HTML);
    }

    public function shareModalButton(): HtmlString
    {
        $data = $this->params['data'];
        $onClick = $this->onClick($data);
        $logo = $this->logo();
        return new HtmlString(<<<HTML
            <button class="basket-atb-button" onClick='{$onClick}'>
                {$logo}
                <span>Add to Basket</span>
            </button>
        HTML);
    }
}
