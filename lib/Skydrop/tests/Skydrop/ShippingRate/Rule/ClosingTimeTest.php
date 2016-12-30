<?php
use PHPUnit\Framework\TestCase;

class ClosingTimeTest extends TestCase
{
    public function testBeforeClosingTime()
    {
        date_default_timezone_set('America/Monterrey');
        $new_time = mktime(17, 0, 0, 10, 10, 2016);
        timecop_freeze($new_time);
        $closingTime = new \Skydrop\ShippingRate\Rule\ClosingTime(
            [], [ 'closingTime' => ['hour' => 20, 'min' => 0] ]
        );
        $this->assertTrue($closingTime->call());
        timecop_return();
    }

    public function testAfterClosingTime()
    {
        date_default_timezone_set('America/Monterrey');
        $new_time = mktime(22, 0, 0, 10, 10, 2016);
        timecop_freeze($new_time);
        $closingTime = new \Skydrop\ShippingRate\Rule\ClosingTime(
            [], [ 'closingTime' => ['hour' => 20, 'min' => 0] ]
        );
        $this->assertNotTrue($closingTime->call());
        timecop_return();
    }
}
