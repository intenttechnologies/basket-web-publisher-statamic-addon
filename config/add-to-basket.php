<?php

return [
    // development, staging or production
    // Set in .env not directly here
    'environment' => env('ADD_TO_BASKET_ENVIRONMENT', 'development'),

    // Set to your api_key supplied to you by Basket
    // Set in .env not directly here
    'api_key' => env('ADD_TO_BASKET_API_KEY', 'xxx'),

    // If you have been assigned a publisher_name, set it here
    'publisher_name' => env('PUBLISHER_NAME', 'default'),

    'basket_path' => env('ADD_TO_BASKET_PATH', 'https://%ENV%.trybasket.com/publisher')
];
