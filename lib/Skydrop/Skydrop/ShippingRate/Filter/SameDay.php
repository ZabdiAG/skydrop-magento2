<?php

namespace Skydrop\ShippingRate\Filter;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class SameDay
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

        if (!$this->shopServiceTime->availableFor('same_day')) {
            $this->removeSameDayRates();
        }
        if ($this->beforeSkydropTime()) {
            $this->changeRatesEndingHour();
        }
        return $this->rates;
    }

    private function removeSameDayRates()
    {
        $this->rates = array_filter(
            $this->rates,
            function($rate) {
                return $rate->service_code != 'Hoy';
            }
        );
    }

    private function changeRatesEndingHour()
    {
        foreach ($this->rates as $rate) {
            if ($rate->service_code != 'Hoy') {
                continue;
            }
            $rate->ending_hour = '22:00';
        }
    }

    private function beforeSkydropTime()
    {
        $d = new \DateTime(\Skydrop\Configs::$skydropOpeningTime);
        return time() <= $d->getTimestamp();
    }
}
