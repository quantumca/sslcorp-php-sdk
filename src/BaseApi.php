<?php

namespace SslCorp;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use SslCorp\Exception\ResponseErrorException;

abstract class BaseApi
{
    private function extra($data, $key)
    {
        return [
            $key => array_merge($data, [
                'account_key' => config('ssl.account_key'),
                'secret_key' => config('ssl.secret_key'),
            ]),
            RequestOptions::CONNECT_TIMEOUT => 600,
            RequestOptions::READ_TIMEOUT => 600,
            RequestOptions::TIMEOUT => 600,
        ];
    }

    private function http()
    {
        return new Client([
            'base_uri' => config('ssl.endpoint', 'https://sws.sslpki.com'),
        ]);
    }

    protected function get($uri, $data)
    {
        logger()->debug('SSL_API_METHOD', ['GET']);
        logger()->debug('SSL_API_URL', [config('ssl.endpoint', 'https://sws.sslpki.com') . $uri]);
        logger()->debug('SSL_API_DATA', $this->extra($data, RequestOptions::QUERY));
        $res = $this->http()->get($uri, $this->extra($data, RequestOptions::QUERY));
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
        logger()->debug('SSL_API_METHOD', ['POST']);
        logger()->debug('SSL_API_URL', [config('ssl.endpoint', 'https://sws.sslpki.com') . $uri]);
        logger()->debug('SSL_API_DATA', $this->extra($data, RequestOptions::JSON));
        $res = $this->http()->post($uri, $this->extra($data, RequestOptions::JSON));
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
