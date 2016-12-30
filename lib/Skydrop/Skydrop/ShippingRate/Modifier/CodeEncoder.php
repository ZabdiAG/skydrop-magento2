<?php

namespace Skydrop\ShippingRate\Modifier;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class CodeEncoder
{
    use \Skydrop\Validations\ValidationTrait;

    public $rates;

    public function __construct($rates = [], $options = array())
    {
        $this->validator = v::attribute('rates', v::arrayType()->length(1, null));

        $this->rates = $rates;
    }

    public function call()
    {
        $this->validate();

        foreach ($this->rates as $rate) {
            $rate->service_code = urlencode($this->stringifiedCode($rate));
        }
        return $this->rates;
    }

    private function stringifiedCode($rate)
    {
        return json_encode([
            'service_code'  => $rate->service_code,
            'vehicle_type'  => $rate->vehicle_type,
            'schedule_date' => $this->_getScheduleDate($rate),
            'starting_hour' => $rate->starting_hour,
            'ending_hour'   => $rate->ending_hour
        ]);
    }

    private function _getScheduleDate($rate)
    {
        // commented to fix bug in Skydrop server
        // if ($rate->service_code == 'next_day') {
        //     return $this->_getNextDayDate();
        // }
        return $this->_getSameDayDate();
    }

    private function _getNextDayDate()
    {
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('America/Monterrey'));
        $date->add(new \DateInterval('P1D')); // Add 1 day;
        return $date->format('Y-m-d');
    }

    private function _getSameDayDate()
    {
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('America/Monterrey'));
        return $date->format('Y-m-d');
    }
}
