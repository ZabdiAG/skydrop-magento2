<?php
use PHPUnit\Framework\TestCase;

class CodeEncoderTest extends TestCase
{
    public function testServiceCode()
    {
        $json_rates = file_get_contents(__DIR__.'/../../../fixtures/shipping_rates.json');
        $rates = json_decode($json_rates);
        $str = json_encode([
            'service_code' => $rates[0]->service_code,
            'vehicle_type' => $rates[0]->vehicle_type,
            'starting_hour' => $rates[0]->starting_hour,
            'ending_hour' => $rates[0]->ending_hour
        ]);
        $klass = new \Skydrop\ShippingRate\Modifier\CodeEncoder($rates);
        $newRates = $klass->call();

        $this->assertEquals($newRates[0]->service_code, $str);
    }
}
