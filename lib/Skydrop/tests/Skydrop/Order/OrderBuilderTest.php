<?php

use PHPUnit\Framework\TestCase;

class Pickup {}
class Delivery {}
class Package {}
class Service {}

class OrderBuilderTest extends TestCase
{
    public function testToHash()
    {
        $order = new \Skydrop\Order\OrderBuilder(
            [
                'pickup'   => $this->getStdStub(),
                'delivery' => $this->getStdStub(),
                'service'  => $this->getStdStub(),
                'package'  => $this->getStdStub()
            ]
        );

        $expected = array_merge(
            $order->getPickup()->toHash(),
            $order->getDelivery()->toHash(),
            $order->getPackage()->toHash(),
            $order->getService()->toHash()
        );

        $this->assertSame($order->toHash(), $expected);
    }

    private function getStdStub()
    {
        $std = $this->getMockBuilder('StdStub')
            ->setMethods(['toHash'])
            ->getMock();

        $array = [
            'std' => [
                'std' => 'std',
            ]
        ];
        $std->expects($this->any())
            ->method('toHash')
            ->will($this->returnValue($array));

        return $std;
    }
}
