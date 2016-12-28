<?php

use PHPUnit\Framework\TestCase;

class PackageTest extends TestCase
{
    public function testToHash()
    {
        $package = new \Skydrop\Order\Package(['codAmount' => 100]);

        $this->assertSame([
            'package' => [
                'cash_on_delivery' => 'true',
                'cod_amount' => '100',
            ],
        ], $package->toHash());
    }
}
