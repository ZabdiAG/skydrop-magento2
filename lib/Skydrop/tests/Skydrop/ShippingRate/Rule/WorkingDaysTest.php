<?php
use PHPUnit\Framework\TestCase;

class WorkingDaysTest extends TestCase
{
    public function testInWorkingDays()
    {
        date_default_timezone_set('America/Monterrey');
        $new_time = mktime(12, 0, 0, 10, 10, 2016);
        timecop_freeze($new_time);
        $workingDays = new \Skydrop\ShippingRate\Rule\WorkingDays(
            [], [ 'workingDays' => [1,2,3,4,5] ]
        );
        $this->assertTrue($workingDays->call());
    }

    public function testNotWorkingDays()
    {
        date_default_timezone_set('America/Monterrey');
        $new_time = mktime(12, 0, 0, 10, 9, 2016);
        timecop_freeze($new_time);
        $workingDays = new \Skydrop\ShippingRate\Rule\WorkingDays(
            [], [ 'workingDays' => [1,2,3,4,5] ]
        );
        $this->assertNotTrue($workingDays->call());
    }
}
