<?php

namespace Skydrop\ShippingRate\Rule;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class ClosingTime
{
    use \Skydrop\Validations\ValidationTrait;

    public $items;
    public $closingTime;
    private $now;

    public function __construct($items = [], $options = array())
    {
        $this->validator = v::attribute('closingTime', v::arrayType()->length(1, null));

        $this->items = $items;
        if (!empty($options['closingTime'])) {
            $this->closingTime = $options['closingTime'];
        }
        $this->now = $this->getDateTimeNow();
    }

    public function call()
    {
        $this->validate();

        if ($this->hoursLeft()) {
            return true;
        }
        return $this->minsLeft();
    }

    private function hoursLeft()
    {
        return $this->closingTime['hour'] > $this->now['hour'];
    }

    private function minsLeft()
    {
        return $this->closingTime['hour'] == $this->now['hour'] && 
            $this->closingTime['min'] > $this->now['min'];
    }

    private function getDateTimeNow()
    {
        $d = new \DateTime();
        $d->setTimezone(new \DateTimeZone('America/Monterrey'));
        return ['hour' => (int)$d->format('H'), 'min' => (int)$d->format('i')];
    }
}
