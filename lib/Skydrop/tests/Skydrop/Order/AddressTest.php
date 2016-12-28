<?php

use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function testToHash()
    {
        $address = new \Skydrop\Order\Address(
            [
                'name' => 'juan rdz',
                'email' => 'juan@gmail.com',
                'streetNameAndNumber' => 'rio rosas 101',
                'municipality' => 'san pedro',
                'neighborhood' => 'del valle',
                'telephone' => '81149090',
            ]
        );

        $this->assertSame([
            'pickup' => [
                'name' => 'juan rdz',
                'email' => 'juan@gmail.com',
                'street_name_and_number' => 'rio rosas 101',
                'municipality' => 'san pedro',
                'neighborhood' => 'del valle',
                'telephone' => '81149090',
            ],
        ], $address->toHash('pickup'));
    }
}
