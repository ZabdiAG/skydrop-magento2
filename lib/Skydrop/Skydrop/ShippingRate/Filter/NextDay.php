<?php

namespace Skydrop\ShippingRate\Filter;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class NextDay
{
    use \Skydrop\Validations\ValidationTrait;

    public $rates;
    public $shopServiceTime;

    public function __construct($rates = [], $options = [])
    {
        $this->validator = v::attribute('rates', v::arrayType()->length(1, null))
            ->attribute('shopServiceTime', v::notEmpty());
        $this->rates = $rates;
        $this->shopServiceTime = \Skydrop\Configs::getShopServiceTime();
    }

    public function call()
    {
        $this->validate();

        if (!$this->shopServiceTime->availableFor('next_day')) {
            $this->removeNextDayRates();
        }
        return $this->rates;
    }

    private function removeNextDayRates()
    {
        $this->rates = array_filter(
            $this->rates,
            function($rate) {
                return $rate->service_code != 'next_day';
            }
        );
    }
}
