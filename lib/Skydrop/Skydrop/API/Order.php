<?php

namespace Skydrop\API;

class Order
{
    const ALL_URL    = '/orders';
    const FIND_URL   = '/orders/:id';
    const SAVE_URL   = '/orders/:id';
    const CREATE_URL = '/orders';
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Requestor();
    }

    public function all()
    {
        return $this->httpClient->request('GET', self::ALL_URL);
    }

    public function find($id)
    {
        $url = str_replace(":id", $id, self::FIND_URL);
        return $this->httpClient->request('GET', $url);
    }

    public function create($params)
    {
        return $this->httpClient->request('POST', self::CREATE_URL, $params);
    }

    public function save($id, $params)
    {
        $url = str_replace(":id", $id, self::SAVE_URL);
        return $this->httpClient->request('PUT', $url, $params);
    }
}
