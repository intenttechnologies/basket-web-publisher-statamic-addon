<?php

namespace Basket\AddToBasket;

use Statamic\Tags\Tags;

class BasketTags extends Tags
{
    protected static $handle = 'basket';

    public function scriptSrc(): string
    {
        $env = config('add-to-basket.environment');
        $replace = $env == "production" ? "www" : $env;
        $path = str_replace("%ENV%", $replace, config('add-to-basket.basket_path'));
        return $path . '/basketShare.js';
    }

    public function scriptTag(): string
    {
        return '<script defer src="' . $this->scriptSrc() . '"></script>';
    }

    public function shareModalOnclick(): string
    {
        $data = $this->params['data'];
        $parsed = array_map(function ($item) {
            return [
                'url' => $item['url'],
                'id' => $item['id']
            ];
        }, $data['links']);
        return '__BASKET__.shareModal({items:' . json_encode($parsed) . '})';
    }
}
