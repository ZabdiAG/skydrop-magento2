<?php

namespace Skydrop\ShippingRate\Rule;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class OpeningTime
{
    use \Skydrop\Validations\ValidationTrait;

    public $items;
    public $openingTime;
    private $now;

    public function __construct($items = [], $options = array())
    {
        $this->validator = v::attribute('openingTime', v::arrayType()->length(1, null));

        $this->items = $items;
        if (!empty($options['openingTime'])) {
            $this->openingTime = $options['openingTime'];
        }
        $this->now = $this->getDateTimeNow();
    }

    public function call()
    {
        $this->validate();

        if ($this->afterOpeningHours()) {
            return true;
        }
        return $this->afterOpeningMins();
    }

    private function afterOpeningHours()
    {
        return $this->openingTime['hour'] < $this->now['hour'];
    }

    private function afterOpeningMins()
    {
        return $this->openingTime['hour'] == $this->now['hour'] &&
            $this->openingTime['min'] < $this->now['min'];
    }

    private function getDateTimeNow()
    {
        $d = new \DateTime();
        $d->setTimezone(new \DateTimeZone('America/Monterrey'));
        return ['hour' => (int)$d->format('H'), 'min' => (int)$d->format('i')];
    }
}
