<?php

namespace Skydrop\API;

class Requestor
{
    public $apiKey;
    private $httpClient;
    private $headers;

    public function __construct()
    {
        $this->apiKey = \Skydrop\Configs::$apiKey;
    }

    public function request($method, $url, $params = [])
    {
        $httpClient = $this->getHttpClient();

        $res = $httpClient->request(
            $method,
            $this->getURL($url),
            ['json' => $params]
        );

        return json_decode($res->getBody());
    }

    private function getURL($resourceURL)
    {
        $baseURL = \Skydrop\Configs::baseURL();
        return "{$baseURL}{$resourceURL}";
    }

    private function getHeaders()
    {
        if (isset($this->headers)) {
            return $this->headers;
        }

        $this->headers = [
            'Authorization' => $this->apiKey,
            'Content-Type' => 'application/json',
        ];

        return $this->headers;
    }

    private function getHttpClient()
    {
        if (isset($this->httpClient)) {
            return $this->httpClient;
        }

        return $this->httpClient = new \GuzzleHttp\Client([
            'headers' => $this->getHeaders()
        ]);
    }
}
