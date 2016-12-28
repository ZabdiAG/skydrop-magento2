<?php
use PHPUnit\Framework\TestCase;

class OpeningTimeTest extends TestCase
{
    public function testAfterOpeningTime()
    {
        date_default_timezone_set('America/Monterrey');
        $new_time = mktime(1, 0, 0, 10, 10, 2016);
        timecop_freeze($new_time);
        $openingTime = new \Skydrop\ShippingRate\Rule\OpeningTime(
            [], [ 'openingTime' => ['hour' => 10, 'min' => 0] ]
        );
        $this->assertNotTrue($openingTime->call());
        timecop_return();
    }

    public function testBeforeOpeningTime()
    {
        date_default_timezone_set('America/Monterrey');
        $new_time = mktime(12, 0, 0, 10, 10, 2016);
        timecop_freeze($new_time);
        $openingTime = new \Skydrop\ShippingRate\Rule\OpeningTime(
            [], [ 'openingTime' => ['hour' => 10, 'min' => 0] ]
        );
        $this->assertTrue($openingTime->call());
        timecop_return();
    }
}
