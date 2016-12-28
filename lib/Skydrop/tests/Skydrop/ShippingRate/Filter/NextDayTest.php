<?php
use PHPUnit\Framework\TestCase;

class NextDayTest extends TestCase
{
    protected function setUp()
    {
        date_default_timezone_set('America/Monterrey');

        $json_shipping_rates = file_get_contents(getcwd().'/tests/fixtures/shipping_rates.json');
        $shipping_rates = json_decode($json_shipping_rates);

        \Skydrop\Configs::setApiKey('abcdefghijk');
        \Skydrop\Configs::setEnv('staging');
        \Skydrop\Configs::setWorkingDays([1,2,3,4,5]);
        \Skydrop\Configs::setOpeningTime([ 'hour' => 9,  'min' => 30 ]);
        \Skydrop\Configs::setClosingTime([ 'hour' => 21, 'min' => 30 ]);

        $this->nextDay = new \Skydrop\ShippingRate\Filter\NextDay(
            $shipping_rates, []
        );
    }
    public function testRejectNextDay()
    {
        $new_time = mktime(22, 0, 0, 10, 8, 2016);
        timecop_freeze($new_time);

        $result = $this->nextDay->call();
        foreach ($result as $rate) {
            $this->assertNotEquals('next_day', $rate->service_code);
        }

        timecop_return();
    }

    public function testIncludeNextDay()
    {
        $new_time = mktime(12, 0, 0, 10, 10, 2016);
        timecop_freeze($new_time);

        $result = $this->nextDay->call();
        $this->assertEquals('next_day', $result[12]->service_code);

        timecop_return();
    }
}
