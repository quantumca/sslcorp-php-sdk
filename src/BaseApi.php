<?php

namespace SslCorp;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use SslCorp\Exception\CsrUniqueValueDuplicatedException;
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

    protected function processErrror(ResponseInterface $response)
    {
        $json = json_decode($response->getBody()->__toString());
        if ($response->getStatusCode() != 200 || (property_exists($json, 'errors') && $json->errors)) {
            if (json_last_error() != JSON_ERROR_NONE) {
                throw new ResponseErrorException($response->getReasonPhrase(), $response->getStatusCode(), null, $response->getBody()->__toString());
            }
            if (Arr::get((array) optional($json)->errors, 'csr.csr_unique_values')) {
                throw new CsrUniqueValueDuplicatedException();
            }
            throw new ResponseErrorException($response->getReasonPhrase(), $response->getStatusCode(), null, optional($json)->errors);
        }
    }

    protected function get($uri, $data)
    {
        logger()->debug('SSL_API_METHOD', ['GET']);
        logger()->debug('SSL_API_URL', [config('ssl.endpoint', 'https://sws.sslpki.com') . $uri]);
        logger()->debug('SSL_API_DATA', $this->extra($data, RequestOptions::QUERY));
        $res = $this->http()->get($uri, $this->extra($data, RequestOptions::QUERY));
        $json = json_decode($res->getBody()->__toString());
        logger()->debug('SSL_API_RES_CODE', [$res->getStatusCode()]);
        logger()->debug('SSL_API_RES_DATA', (array) $json);
        $this->processErrror($res);
        return json_decode($res->getBody()->__toString());
    }

    protected function post($uri, $data)
    {
        logger()->debug('SSL_API_METHOD', ['POST']);
        logger()->debug('SSL_API_URL', [config('ssl.endpoint', 'https://sws.sslpki.com') . $uri]);
        logger()->debug('SSL_API_DATA', $this->extra($data, RequestOptions::JSON));
        $res = $this->http()->post($uri, $this->extra($data, RequestOptions::JSON));
        $json = json_decode($res->getBody()->__toString());
        logger()->debug('SSL_API_RES_CODE', [$res->getStatusCode()]);
        logger()->debug('SSL_API_RES_DATA', (array) $json);
        $this->processErrror($res);
        return $json;
    }

    protected function put($uri, $data)
    {
        logger()->debug('SSL_API_METHOD', ['PUT']);
        logger()->debug('SSL_API_URL', [config('ssl.endpoint', 'https://sws.sslpki.com') . $uri]);
        logger()->debug('SSL_API_DATA', $this->extra($data, RequestOptions::JSON));
        $res = $this->http()->put($uri, $this->extra($data, RequestOptions::JSON));
        $json = json_decode($res->getBody()->__toString());
        logger()->debug('SSL_API_RES_CODE', [$res->getStatusCode()]);
        logger()->debug('SSL_API_RES_DATA', (array) $json);
        $this->processErrror($res);
        return $json;
    }
}
