<?php

namespace Skydrop\ShippingRate\Rule;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class WorkingDays
{
    use \Skydrop\Validations\ValidationTrait;

    public $items;
    public $workingDays;

    public function __construct($items = [], $options = array())
    {
        $this->validator = v::attribute('workingDays', v::arrayType()->length(1, null));

        $this->items = $items;
        if (!empty($options['workingDays'])) {
            $this->workingDays = $options['workingDays'];
        }
    }

    public function call()
    {
        $this->validate();

        return in_array($this->todayWday(), $this->workingDays);
    }

    private function todayWday()
    {
        $d = new \DateTime();
        return (int)$d->format('w');
    }
}
