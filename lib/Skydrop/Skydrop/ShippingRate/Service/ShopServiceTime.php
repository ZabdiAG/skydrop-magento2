<?php

namespace Skydrop\ShippingRate\Service;

class ShopServiceTime
{
    public $workingDays;
    public $openingTime;
    public $closingTime;

    public function __construct($options = [])
    {
        if (!empty($options['workingDays'])) {
            $this->workingDays = $options['workingDays'];
        }
        if (!empty($options['openingTime'])) {
            $this->openingTime = $options['openingTime'];
        }
        if (!empty($options['closingTime'])) {
            $this->closingTime = $options['closingTime'];
        }
        $this->setDefaults();
    }

    public function availableFor($serviceType = 'same_day')
    {
        switch ($serviceType) {
        case 'express':
            return $this->inWorkingDays() && $this->opened() && !$this->closed();
        case 'next_day':
            return $this->openTomorrow();
        default:
            return $this->inWorkingDays() && !$this->closed() && $this->endOfDay();
        }
    }

    private function opened()
    {
        $openingTime = $this->openingTimeRule();
        return $openingTime->call();
    }

    private function closed()
    {
        $closingTime = $this->closingTimeRule();
        return !$closingTime->call();
    }

    private function inWorkingDays()
    {
        $workingDays = $this->workingDaysRule();
        return $workingDays->call();
    }

    private function openTomorrow()
    {
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('America/Monterrey'));
        $date->add(new \DateInterval('P1D')); // Add 1 day;
        $day = (int)$date->format('w');
        return in_array($day, $this->workingDays);
    }

    private function endOfDay()
    {
        $stamp = mktime(23, 59, 59); // End of day;
        return time() < $stamp;
    }

    private function openingTimeRule()
    {
        return new \Skydrop\ShippingRate\Rule\OpeningTime(
            [], [ 'openingTime' => $this->openingTime ]
        );
    }

    private function closingTimeRule()
    {
        return new \Skydrop\ShippingRate\Rule\ClosingTime(
            [], [ 'closingTime' => $this->closingTime ]
        );
    }

    private function workingDaysRule()
    {
        return new \Skydrop\ShippingRate\Rule\WorkingDays(
            [], [ 'workingDays' => $this->workingDays ]
        );
    }

    private function setDefaults()
    {
        if (empty($this->workingDays)) {
            $this->workingDays = [1,2,3,4,5];
        }
        if (empty($this->openingTime)) {
            $this->openingTime = [ 'hour' => 10, 'min' => 0 ];
        }
        if (empty($this->closingTime)) {
            $this->closingTime = [ 'hour' => 22, 'min' => 0 ];
        }
    }
}
