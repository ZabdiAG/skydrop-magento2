<?php
use PHPUnit\Framework\TestCase;

class ConfigsTest extends TestCase
{
    public function testSetApiKey()
    {
        $apiKey = 'abcdefg';
        \Skydrop\Configs::setApiKey('abcdefg');
        $actual = \Skydrop\Configs::$apiKey;

        $this->assertEquals($apiKey, $actual);
    }

    public function testSetFilters()
    {
        $defaults = [
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Filter\\SameDay',
                'options' => []
            ),
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Filter\\NextDay',
                'options' => []
            ),
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Filter\\Express',
                'options' => []
            ),
        ];
        $filters = [
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Filter\\OnePerService',
                'options' => []
            )
        ];
        \Skydrop\Configs::setFilters($filters);
        $actual = \Skydrop\Configs::getFilters();

        $this->assertEquals(array_merge($filters, $defaults), $actual);
    }
}
