<?php

namespace SslCorp;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use SslCorp\Exception\CsrUniqueValueDuplicatedException;
use SslCorp\Exception\ResponseErrorException;

abstract class BaseApi
{
    protected $config = [];

    public function __construct($config = [])
    {
        $this->config = config('ssl');
        $this->config = array_merge($this->config, $config);
    }

    protected function replaceNullWithEmpty($data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $data[$key] = $this->replaceNullWithEmpty($value);
            } else if ($value === null) {
                $data[$key] = '';
            }
        }

        return $data;
    }

    private function extra($data, $key)
    {
        return [
            $key => array_merge($this->replaceNullWithEmpty($data), [
                'account_key' => $this->config['account_key'],
                'secret_key' => $this->config['secret_key'],
            ]),
            RequestOptions::CONNECT_TIMEOUT => $this->config['connect_timeout'] ?? 3,
            RequestOptions::READ_TIMEOUT => $this->config['read_timeout'] ?? 500,
            RequestOptions::TIMEOUT => $this->config['timeout'] ?? 500,
            RequestOptions::VERIFY => $this->config['verify'] ?? false,
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
        try {
            $res = $this->http()->get($uri, $this->extra($data, RequestOptions::QUERY));
        } catch (ServerException $e) {
            logger()->debug('SSL_API_RES_HTML', [$e->getResponse()->getBody()->__toString()]);
            throw $e;
        }
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
        try {
            $res = $this->http()->post($uri, $this->extra($data, RequestOptions::JSON));
        } catch (ServerException $e) {
            logger()->debug('SSL_API_RES_HTML', [$e->getResponse()->getBody()->__toString()]);
            throw $e;
        }
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
        try {
            $res = $this->http()->put($uri, $this->extra($data, RequestOptions::JSON));
        } catch (ServerException $e) {
            logger()->debug('SSL_API_RES_HTML', [$e->getResponse()->getBody()->__toString()]);
            throw $e;
        }
        $json = json_decode($res->getBody()->__toString());
        logger()->debug('SSL_API_RES_CODE', [$res->getStatusCode()]);
        logger()->debug('SSL_API_RES_DATA', (array) $json);
        $this->processErrror($res);
        return $json;
    }

    protected function delete($uri, $data)
    {
        logger()->debug('SSL_API_METHOD', ['DELETE']);
        logger()->debug('SSL_API_URL', [config('ssl.endpoint', 'https://sws.sslpki.com') . $uri]);
        logger()->debug('SSL_API_DATA', $this->extra($data, RequestOptions::JSON));
        try {
            $res = $this->http()->delete($uri, $this->extra($data, RequestOptions::JSON));
        } catch (ServerException $e) {
            logger()->debug('SSL_API_RES_HTML', [$e->getResponse()->getBody()->__toString()]);
            throw $e;
        }
        $json = json_decode($res->getBody()->__toString());
        logger()->debug('SSL_API_RES_CODE', [$res->getStatusCode()]);
        logger()->debug('SSL_API_RES_DATA', (array) $json);
        $this->processErrror($res);
        return $json;
    }
}
