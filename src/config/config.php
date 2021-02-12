<?php

return [
    'account_key' => env('SSL_ACCOUNT_KEY'),
    'secret_key' => env('SSL_SECRET_KEY'),
    'timeout' => env('SSL_TIMEOUT'),
    'connect_timeout' => env('SSL_CONNECT_TIMEOUT'),
    'read_timeout' => env('SSL_READ_TIMEOUT'),
    'endpoint' => env('SSL_ENDPOINT', 'https://sws.sslpki.com'),
];
