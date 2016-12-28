<?php
use PHPUnit\Framework\TestCase;

class ShippingRateBuilderTest extends TestCase
{
    public function testToHash()
    {
        $shippingRate = new \Skydrop\ShippingRate\ShippingRateBuilder(
            [
                'origin' => $this->getStdStub(),
                'destination' => $this->getStdStub()
            ]
        );

        $this->assertSame(
            [
                'rate' => array_merge(
                    $shippingRate->getOrigin()->toHash(),
                    $shippingRate->getDestination()->toHash()
                )
            ],
            $shippingRate->toHash()
        );
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
