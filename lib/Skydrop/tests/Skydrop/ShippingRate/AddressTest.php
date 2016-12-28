<?php

use PHPUnit\Framework\TestCase;

class ShippingRateAddressTest extends TestCase
{
    public function testToHash()
    {
        $address = new \Skydrop\ShippingRate\Address(
            [
                'name'        => 'raul',
                'phone'       => '81818181',
                'address1'    => 'rio guadalquivir',
                'address2'    => '',
                'city'        => 'san pedro',
                'province'    => 'nuevo leon',
                'postal_code' => '66220',
                'country'     => 'mx'
            ]
        );

        $this->assertSame(
            [
                'pickup' => [
                    'name'        => 'raul',
                    'phone'       => '81818181',
                    'address1'    => 'rio guadalquivir',
                    'address2'    => '',
                    'city'        => 'san pedro',
                    'province'    => 'nuevo leon',
                    'postal_code' => '66220',
                    'country'     => 'mx'
                ]
            ],
            $address->toHash('pickup')
        );
    }
}
