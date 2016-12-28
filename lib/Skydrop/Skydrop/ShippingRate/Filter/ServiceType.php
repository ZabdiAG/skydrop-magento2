<?php

namespace Skydrop\ShippingRate\Filter;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class ServiceType
{
    use \Skydrop\Validations\ValidationTrait;

    public $rates;
    public $serviceTypes;

    public function __construct($rates = [], $options = array())
    {
        $this->validator = v::attribute('rates', v::arrayType()->length(1, null))
            ->attribute('serviceTypes', v::arrayType()->length(1, null));

        $this->rates = $rates;
        $this->serviceTypes = $options['serviceTypes'];
    }

    public function call()
    {
        $this->validate();

        $filteredRates = [];
        foreach ($this->rates as $rate) {
            if (in_array($rate->service_code, $this->service_types) {
                $filteredRates[] = $rate;
            }
        }
        return $filteredRates;
    }
}
