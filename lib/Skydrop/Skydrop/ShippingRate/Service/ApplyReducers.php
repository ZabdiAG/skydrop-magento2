<?php

namespace Skydrop\ShippingRate\Service;

class ApplyReducers
{
    public $rawRates;

    public $filters;

    public $shippingParams;

    public function __construct($rawRates = [], $filters = [], $shippingParams = array())
    {
        $this->rawRates = $rawRates;
        $this->filters = $filters;
        $this->shippingParams = $shippingParams;
    }

    public function call()
    {
        if (empty($this->rawRates)) {
            return [];
        }
        if ($this->filters == []) {
            return $this->rawRates;
        }
        return $this->filteredRates();
    }

    private function filteredRates()
    {
        $filteredRates = $this->rawRates;

        foreach ($this->filters as $filter) {
            try {
                if (empty($filter->options)) {
                    $filter->options = [];
                }
                $options = ['shipping' => $this->shippingParams];
                $options = array_merge(
                    ['shipping' => $this->shippingParams],
                    $filter->options
                );
                $klass = new $filter->klass($filteredRates, $options);
                $filteredRates = $klass->call();
            } catch (Exception $e) {
                \Skydrop\Configs::notifyErrbit($e);
            }
        }

        return $filteredRates;
    }
}
