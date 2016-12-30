<?php

namespace Skydrop\API;

class ShippingRate
{
    const ALL_URL  = '/shipping_rates';
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Requestor();
    }

    public function all($params)
    {
        return $this->httpClient->request('POST', self::ALL_URL, $params);
    }
}
