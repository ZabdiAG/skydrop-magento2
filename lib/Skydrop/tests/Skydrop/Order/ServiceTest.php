<?php

use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    public function testToHash()
    {
        $service = new \Skydrop\Order\Service(
            [
                'serviceCode'  => 'Hoy',
                'vehicleType'  => 'car',
                'scheduleDate' => '2016-10-10',
                'startingHour' => '10:00',
                'endingHour'   => '22:00'
            ]
        );

        $this->assertSame([
            'service' => [
               'service_code'  => 'Hoy',
               'vehicle_type'  => 'car',
               'schedule_date' => '2016-10-10',
               'starting_hour' => '10:00',
               'ending_hour'   => '22:00'
            ]
        ], $service->toHash());
    }
}
