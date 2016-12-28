<?php

namespace Skydrop\Order;

class Service
{
    use \Skydrop\Traits\GettersAndSettersTrait;

    private $serviceCode;
    private $vehicleType;
    private $scheduleDate;
    private $startingHour;
    private $endingHour;

    public function __construct($args)
    {
        $this->serviceCode  = $this->getFromArray('serviceCode', $args, '');
        $this->vehicleType  = $this->getFromArray('vehicleType', $args, '');
        $this->scheduleDate = $this->getFromArray('scheduleDate', $args, '');
        $this->startingHour = $this->getFromArray('startingHour', $args, '');
        $this->endingHour   = $this->getFromArray('endingHour', $args, '');
    }

    public function toHash()
    {
        return array(
            'service' => array(
                'service_code'  => $this->serviceCode,
                'vehicle_type'  => $this->vehicleType,
                'schedule_date' => $this->scheduleDate,
                'starting_hour' => $this->startingHour,
                'ending_hour'   => $this->endingHour,
            )
        );
    }
}
