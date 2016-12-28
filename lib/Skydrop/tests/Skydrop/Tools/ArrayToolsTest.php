<?php
use PHPUnit\Framework\TestCase;

require_once 'Skydrop/Tools/ArrayTools.php';

class ArrayToolsTest extends TestCase
{
    public function testMergeArrayOfObjectsByKey()
    {
        $defaultArray = [
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Modifier\\ServiceName',
                'options' => []
            ),
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Modifier\\CodeEncoder',
                'options' => []
            )
        ];

        $myArray = [
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Modifier\\ServiceName',
                'options' => [ 'serviceNames' => ['same_day' => 'here is my custom message'] ]
            )
        ];

        $expectedArray = [
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Modifier\\ServiceName',
                'options' => [ 'serviceNames' => ['same_day' => 'here is my custom message'] ]
            ),
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Modifier\\CodeEncoder',
                'options' => []
            )
        ];

        $this->assertEquals(
            $expectedArray,
            mergeArrayByKey($defaultArray, $myArray, 'klass')
        );
    }
}
