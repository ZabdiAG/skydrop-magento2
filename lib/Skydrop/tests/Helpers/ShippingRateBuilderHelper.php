<?php

class ShippingRateBuilderHelper
{
    public function __construct()
    {
        $this->getRawRates();
    }

    public function getShippingRateBuilder()
    {
        return new Skydrop\ShippingRate\ShippingRateBuilder(
            [
                'origin' => $this->getOrigin(),
                'destination' => $this->getDestination(),
                'items' => $this->getItems()
            ]
        );
    }

    private function getOrigin()
    {
        return new Skydrop\ShippingRate\Address(
            (array)$this->rates->origin
        );
    }

    private function getDestination()
    {
        return new Skydrop\ShippingRate\Address(
            (array)$this->rates->destination
        );
    }

    private function getItems()
    {
        return array_map(
            function($item) {
                return new \Skydrop\ShippingRate\Item((array)$item);
            },
            $this->rates->items
        );
    }

    private function getRawRates()
    {
        $json_rates = file_get_contents(getcwd().'/tests/fixtures/rates.json');
        $this->rates = json_decode($json_rates)->rate;
    }
}
