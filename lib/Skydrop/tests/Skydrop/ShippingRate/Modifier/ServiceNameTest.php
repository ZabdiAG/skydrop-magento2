<?php
use PHPUnit\Framework\TestCase;

class ServiceNameTest extends TestCase
{
    public function testSameDayServiceName()
    {
        $json_rates = file_get_contents(getcwd().'/tests/fixtures/shipping_rates.json');
        $rates = json_decode($json_rates);
        $klass = new \Skydrop\ShippingRate\Modifier\ServiceName(
            $rates, 
            ['serviceNames' => ['same_day' => 'here is my custom message']]
        );
        $newRates = $klass->call();

        $name = 'Skydrop - Mismo Dia, te llega antes de las 10:00 pm (here is my custom message)';

        $this->assertEquals($newRates[4]->service_name, $name);
    }

    public function testEExpsServiceName()
    {
        $json_rates = file_get_contents(getcwd().'/tests/fixtures/shipping_rates.json');
        $rates = json_decode($json_rates);
        $klass = new \Skydrop\ShippingRate\Modifier\ServiceName($rates);
        $newRates = $klass->call();

        $name = 'Skydrop - Express, te llega antes de las 15:39 PM';

        $this->assertEquals($newRates[0]->service_name, $name);
    }

    public function testNextDayServiceName()
    {
        $json_rates = file_get_contents(getcwd().'/tests/fixtures/shipping_rates.json');
        $rates = json_decode($json_rates);
        $klass = new \Skydrop\ShippingRate\Modifier\ServiceName($rates);
        $newRates = $klass->call();

        $date = new \DateTime();
        $date->add(new \DateInterval('P1D'));
        $naxtDayDate = $date->format('D d M');
        $name = "Skydrop - Siguiente Día, te llega el día {$naxtDayDate} antes de las 10:00 pm";

        $this->assertEquals($newRates[2]->service_name, $name);
    }
}
