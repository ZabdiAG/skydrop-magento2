<?php
use PHPUnit\Framework\TestCase;
require_once 'tests/Helpers/ShippingRateBuilderHelper.php';

class SearchTest extends TestCase
{
    protected function setUp()
    {
        date_default_timezone_set('America/Monterrey');
        \Skydrop\Configs::setApiKey('abcdefghijk');
        \Skydrop\Configs::setEnv('staging');
        \Skydrop\Configs::setWorkingDays([1,2,3,4,5]);
        \Skydrop\Configs::setOpeningTime(array('hour' => 9, 'min' => 30));
        \Skydrop\Configs::setClosingTime(array('hour' => 21, 'min' => 30));

        $shippingRateHelper = new \ShippingRateBuilderHelper();
        $this->builder = $shippingRateHelper->getShippingRateBuilder();

        $new_time = mktime(12, 0, 0, 10, 10, 2016);
        timecop_freeze($new_time);
    }

    public function testCallWithFilters()
    {
        \Skydrop\Configs::setFilters($this->getFilters());

        $search = new \Skydrop\ShippingRate\Search($this->builder);

        \VCR\VCR::configure()->setCassettePath(getcwd().'/tests/VCR');
        \VCR\VCR::turnOn();
        \VCR\VCR::insertCassette('shipping_rates');

        $result = $search->call();
        $this->assertEquals(1, count($result));
        foreach ($result as $rate) {
            $this->assertEquals($rate->vehicle_type, 'car');
        }

        \VCR\VCR::eject();
        \VCR\VCR::turnOff();
    }

    public function testCallWithModifiers()
    {
        \Skydrop\Configs::setFilters($this->getFilters());

        $search = new \Skydrop\ShippingRate\Search($this->builder);

        \VCR\VCR::configure()->setCassettePath(getcwd().'/tests/VCR');
        \VCR\VCR::turnOn();
        \VCR\VCR::insertCassette('shipping_rates');

        $result = $search->call();
        $name = 'Skydrop - Mismo Dia, te llega antes de las 10:00 pm';
        $this->assertEquals(1, count($result));
        foreach ($result as $rate) {
            $code = json_encode([
                'service_code' => 'Hoy',
                'vehicle_type' => $rate->vehicle_type,
                'starting_hour' => $rate->starting_hour,
                'ending_hour' => $rate->ending_hour
            ]);
            $this->assertEquals($rate->service_code, $code);
            $this->assertEquals($rate->service_name, $name);
            $this->assertEquals($rate->vehicle_type, 'car');
        }

        \VCR\VCR::eject();
        \VCR\VCR::turnOff();
    }

    public function testCallWithRules()
    {
        \Skydrop\Configs::setFilters($this->getFilters());
        \Skydrop\Configs::setRules($this->getRules());

        $search = new \Skydrop\ShippingRate\Search($this->builder);

        \VCR\VCR::configure()->setCassettePath(getcwd().'/tests/VCR');
        \VCR\VCR::turnOn();
        \VCR\VCR::insertCassette('shipping_rates');

        $result = $search->call();
        $this->assertEquals(1, count($result));
        foreach ($result as $rate) {
            $code = json_encode([
                'service_code' => 'Hoy',
                'vehicle_type' => $rate->vehicle_type,
                'starting_hour' => $rate->starting_hour,
                'ending_hour' => $rate->ending_hour
            ]);
            $this->assertEquals($rate->service_code, $code);
            $this->assertEquals($rate->vehicle_type, 'car');
        }

        \VCR\VCR::eject();
        \VCR\VCR::turnOff();
    }

    private function getFilters()
    {
        return [
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Filter\\OnePerService',
                'options' => [ 'serviceTypes' => ['Hoy'] ]
            ),
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Filter\\VehicleType',
                'options' => [ 'vehicleTypes' => ['car'] ]
            )
        ];
    }

    private function getRules()
    {
        return [ ];
    }
}
