<?php

namespace SslCorp;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

abstract class BaseApi
{
    private function http()
    {
        return new Client([
            'base_uri' => config('ssl.endpoint', 'https://sws.sslpki.com'),
        ]);
    }

    protected function get($uri, $data)
    {
        $this->http()->get($uri, [
            RequestOptions::QUERY => array_merge($data, [
                'account_key' => config('ssl.account_key'),
                'secret_key' => config('ssl.secret_key'),
            ]),
        ]);
    }

    protected function post($uri, $data)
    {
        $this->http()->post($uri, [
            RequestOptions::JSON => array_merge($data, [
                'account_key' => config('ssl.account_key'),
                'secret_key' => config('ssl.secret_key'),
            ]),
        ]);
    }
}
