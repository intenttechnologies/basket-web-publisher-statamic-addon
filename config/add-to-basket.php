<?php

/****************************************
 * 
 * SET VALUES IN .env NOT DIRECTLY HERE
 * 
 ****************************************/

return [
    // development, staging or production
    // Set in .env not directly here
    'environment' => env('ADD_TO_BASKET_ENVIRONMENT', 'development'),

    // Set to your api_key supplied to you by Basket
    // Set in .env not directly here
    'api_key' => env('ADD_TO_BASKET_API_KEY', 'xxx'),

    // Set to your publisher_name if you have been assigned one
    // Set in .env not directly here
    'publisher_name' => env('ADD_TO_BASKET_PUBLISHER_NAME', 'default'),

    // Only set for local development
    'basket_path' => env('ADD_TO_BASKET_PATH', 'https://%ENV%.trybasket.com/publisher'),

    // Debug logging
    'enable_debug_log' => env('ADD_TO_BASKET_ENABLE_DEBUG_LOG', false)
];
