<?php

return [
    // development, staging or production
    'environment' => env('ADD_TO_BASKET_ENVIRONMENT', 'development'),

    // Set to your api_key supplied to you by Basket
    'api_key' => env('ADD_TO_BASKET_API_KEY', 'xxx'),

    'basket_path' => env('ADD_TO_BASKET_PATH', 'https://%ENV%.trybasket.com/publisher'),
];
