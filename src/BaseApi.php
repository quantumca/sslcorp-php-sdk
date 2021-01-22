<?php

namespace SslCorp;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use SslCorp\Exception\ResponseErrorException;

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
        if ($res->getStatusCode() != 200) {
            $json = json_decode($res->getBody()->__toString());
            if (json_last_error() != JSON_ERROR_NONE) {
                throw new ResponseErrorException($res->getReasonPhrase(), $res->getStatusCode(), null, $res->getBody()->__toString());
            }
            throw new ResponseErrorException($res->getReasonPhrase(), $res->getStatusCode(), null, optional($json)->errors);
        }
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
        if ($res->getStatusCode() != 200) {
            $json = json_decode($res->getBody()->__toString());
            if (json_last_error() != JSON_ERROR_NONE) {
                throw new ResponseErrorException($res->getReasonPhrase(), $res->getStatusCode(), null, $res->getBody()->__toString());
            }
            throw new ResponseErrorException($res->getReasonPhrase(), $res->getStatusCode(), null, optional($json)->errors);
        }
        return json_decode($res->getBody()->__toString());
    }
}
