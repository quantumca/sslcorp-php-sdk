<?php

return [
    'account_key' => env('SSL_ACCOUNT_KEY'),
    'secret_key' => env('SSL_SECRET_KEY'),
    'timeout' => env('SSL_TIMEOUT'),
    'endpoint' => env('SSL_ENDPOINT', 'https://sws.sslpki.com'),
];
