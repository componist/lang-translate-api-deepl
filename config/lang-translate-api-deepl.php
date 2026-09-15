<?php

return [
    'api_key' => env('DEEPL_API_KEY'),
    'api_url' => env('DEEPL_API_URL', 'https://api-free.deepl.com/v2/translate'),
    'timeout_seconds' => (int) env('DEEPL_API_TIMEOUT', 30),
];
