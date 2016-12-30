<?php

namespace Skydrop\ShippingRate\Filter;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class VehicleType
{
    use \Skydrop\Validations\ValidationTrait;

    public $rates;
    public $vehicleTypes;

    public function __construct($rates = [], $options = array())
    {
        $this->validator = v::attribute('rates', v::arrayType()->length(1, null))
            ->attribute('vehicleTypes', v::arrayType()->length(1, null));

        $this->rates = $rates;
        $this->vehicleTypes = $options['vehicleTypes'];
    }

    public function call()
    {
        $this->validate();

        $filteredRates = [];
        foreach ($this->rates as $rate) {
            if (in_array($rate->vehicle_type, $this->vehicleTypes)) {
                $filteredRates[] = $rate;
            }
        }
        return $filteredRates;
    }
}
