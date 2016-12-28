<?php

namespace Skydrop\ShippingRate;

class Search
{
    public $itemsParams;

    public $shippingParams;

    public $filters;

    public $modifiers;

    public $rules;

    public function __construct($shippingRate)
    {
        $this->itemsParams    = $shippingRate->getItems();
        $this->shippingParams = $shippingRate->toHash();
        $this->filters        = \Skydrop\Configs::getFilters();
        $this->modifiers      = \Skydrop\Configs::getModifiers();
        $this->rules          = \Skydrop\Configs::$rules;
    }

    public function call()
    {
        try {
            if (!$this->orderEligible()) {
                return [];
            }
            $foundRates = $this->getRawRates();
            if (empty($foundRates)) {
                return [];
            }

            return $this->applyModifiers($this->applyFilters($foundRates));
        } catch (\Exception $e) {
            \Skydrop\Configs::notifyErrbit($e);
            return [];
        }
    }

    private function orderEligible()
    {
        if (empty($this->rules)) {
            return true;
        }

        $rules = array_map(
            function($rate) {
                $klass = new $rate->klass($this->itemsParams, $rate->options);
                return $klass->call();
            },
            $this->rules
        );

        foreach ($rules as $rule) {
            if (!$rule) {
                return false;
            }
        }
        return true;
    }

    public function getRawRates()
    {
        $klass    = new \Skydrop\API\ShippingRate();
        $response = $klass->all($this->shippingParams);
        $rates    = $response->rates;
        if (empty($rates)) {
            return [];
        } else {
            return $rates;
        }
    }

    private function applyModifiers($rates)
    {
        $reducer = new \Skydrop\ShippingRate\Service\ApplyReducers(
            $rates, $this->modifiers, $this->shippingParams
        );
        return $reducer->call();
    }

    private function applyFilters($rates)
    {
        $reducer = new \Skydrop\ShippingRate\Service\ApplyReducers(
            $rates, $this->filters, $this->shippingParams
        );
        return $reducer->call();
    }
}
