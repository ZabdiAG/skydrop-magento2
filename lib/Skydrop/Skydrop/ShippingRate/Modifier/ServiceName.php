<?php

namespace Skydrop\ShippingRate\Modifier;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class ServiceName
{
    use \Skydrop\Validations\ValidationTrait;

    public $rates;
    public $serviceNames;

    public function __construct($rates = [], $options = array())
    {
        $this->validator = v::attribute('rates', v::arrayType()->length(1, null));

        $this->rates = $rates;
        if (!empty($options['serviceNames'])) {
            $this->serviceNames = $options['serviceNames'];
        }
    }

    public function call()
    {
        $this->validate();

        foreach ($this->rates as $rate) {
            $rate->service_name = $this->modifiedServiceName($rate);
        }
        return $this->rates;
    }

    private function modifiedServiceName($rate)
    {
        switch ($rate->service_code) {
        case 'EExps':
            return $this->eexpsName($rate);
            break;
        case 'Hoy':
            return $this->sameDayName();
            break;
        case 'next_day':
            return $this->nextDayName();
            break;
        default:
            return $rate->service_name;
        }
    }

    private function sameDayName()
    {
        return "Skydrop - Mismo Dia, te llega antes de las 10:00 pm{$this->shopServiceName('same_day')}";
    }

    private function eexpsName($rate)
    {
        return "Skydrop - Express, te llega antes de las {$this->toAmPm($rate->ending_hour)}{$this->shopServiceName('eexps')}";
    }

    private function nextDayName()
    {
        return "Skydrop - Siguiente Día, te llega el día {$this->nextDayDate()} antes de las 10:00 pm{$this->shopServiceName('next_day')}";
    }

    private function toAmPm($timeStr)
    {
        $date = new \DateTime($timeStr, new \DateTimeZone('America/Monterrey'));
        return $date->format('H:i A');
    }

    private function nextDayDate()
    {
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('America/Monterrey'));
        $date->add(new \DateInterval('P1D')); // Add 1 day;
        return $date->format('D d M');
    }

    private function shopServiceName($type)
    {
        if (empty($this->serviceNames[$type])) { return ''; }

        return " ({$this->serviceNames[$type]})";
    }
}
