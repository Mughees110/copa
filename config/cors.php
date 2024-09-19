<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'csrf-token'], // Adjust to include your CSRF endpoint

    'allowed_methods' => ['*'], // You can specify specific methods like ['GET', 'POST']

    'allowed_origins' => ['http://localhost:3000','*'], // Allow requests from your React app

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // You can specify specific headers if needed

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
