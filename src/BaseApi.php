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
        $res = $this->http()->get($uri, [
            RequestOptions::QUERY => array_merge($data, [
                'account_key' => config('ssl.account_key'),
                'secret_key' => config('ssl.secret_key'),
            ]),
        ]);
        return json_decode($res->getBody()->__toString());
    }

    protected function post($uri, $data)
    {
        $res = $this->http()->post($uri, [
            RequestOptions::JSON => array_merge($data, [
                'account_key' => config('ssl.account_key'),
                'secret_key' => config('ssl.secret_key'),
            ]),
        ]);
        return json_decode($res->getBody()->__toString());
    }
}
