<?php
use PHPUnit\Framework\TestCase;

class OnePerServiceTest extends TestCase
{
    public function testReturnSameDayRates()
    {
        $json_rates = file_get_contents(__DIR__.'/../../../fixtures/shipping_rates.json');
        $rates = json_decode($json_rates);
        $klass = new \Skydrop\ShippingRate\Filter\OnePerService(
            $rates, ['serviceTypes' => ['Hoy']]
        );
        $newRates = $klass->call();

        $this->assertEquals(count($newRates), 1);
    }

    public function testReturnAllRates()
    {
        $json_rates = file_get_contents(__DIR__.'/../../../fixtures/shipping_rates.json');
        $rates = json_decode($json_rates);
        $klass = new \Skydrop\ShippingRate\Filter\OnePerService(
            $rates, ['serviceTypes' => ['Hoy', 'EExps', 'next_day']]
        );
        $newRates = $klass->call();

        $this->assertEquals(count($newRates), 5);
    }
}
