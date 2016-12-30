<?php

namespace Skydrop\ShippingRate\Filter;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class OnePerService
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
        foreach ($this->serviceTypes as $serviceType) {
            foreach ($this->rates as $rate) {
                if ($rate->service_code == $serviceType) {
                    $filteredRates[] = $rate;
                    break 1;
                }
            }
        }
        return $filteredRates;
    }
}
