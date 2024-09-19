<?php
return [

    'paths' => ['api/*', 'csrf-token'], // Adjust to include your CSRF endpoint

    'allowed_methods' => ['*'], // You can specify specific methods like ['GET', 'POST']

    'allowed_origins' => ['http://localhost:3000'], // Allow requests from your React app

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // You can specify specific headers if needed

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Set to true if you need to send cookies or HTTP authentication

];
