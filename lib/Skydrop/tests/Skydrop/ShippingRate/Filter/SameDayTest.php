<?php
use PHPUnit\Framework\TestCase;

class SameDayTest extends TestCase
{
    protected function setUp()
    {
        date_default_timezone_set('America/Monterrey');

        $json_shipping_rates = file_get_contents(getcwd().'/tests/fixtures/shipping_rates.json');
        $shipping_rates = json_decode($json_shipping_rates);

        \Skydrop\Configs::setApiKey('abcdefghijk');
        \Skydrop\Configs::setEnv('staging');
        \Skydrop\Configs::setWorkingDays([1,2,3,4,5]);
        \Skydrop\Configs::setOpeningTime([ 'hour' => 9, 'min' => 30 ]);
        \Skydrop\Configs::setClosingTime([ 'hour' => 21, 'min' => 30 ]);

        $this->sameDay = new \Skydrop\ShippingRate\Filter\SameDay(
            $shipping_rates, []
        );
    }

    public function testRejectSameDay()
    {
        $new_time = mktime(22, 0, 0, 10, 8, 2016);
        timecop_freeze($new_time);

        $result = $this->sameDay->call();
        foreach ($result as $rate) {
            $this->assertNotEquals('Hoy', $rate->service_code);
        }

        timecop_return();
    }

    public function testIncludeSameDay()
    {
        $new_time = mktime(12, 0, 0, 10, 10, 2016);
        timecop_freeze($new_time);

        $result = $this->sameDay->call();
        // var_dump($result);
        $this->assertEquals('Hoy', $result[4]->service_code);

        timecop_return();
    }

    public function testChangeEndingHour()
    {
        $new_time = mktime(2, 0, 0, 10, 10, 2016);
        timecop_freeze($new_time);

        $result = $this->sameDay->call();
        $this->assertEquals('22:00', $result[4]->ending_hour);

        timecop_return();
    }
}
