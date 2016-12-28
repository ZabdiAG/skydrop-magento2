<?php

use PHPUnit\Framework\TestCase;
require_once 'tests/Helpers/OrdersHelper.php';

class CreateTest extends TestCase
{
    public function setUp()
    {
        \Skydrop\Configs::setApiKey('abcdefghijk');
        \Skydrop\Configs::setEnv('staging');
        \VCR\VCR::configure()->setCassettePath(getcwd().'/tests/VCR');
        \VCR\VCR::turnOn();
    }

    public function testCallReturnSuccess()
    {
        \VCR\VCR::insertCassette('create_order_success');

        $orderHelper = new \OrdersHelper();
        $order = $orderHelper->getValidOrder();
        $this->assertTrue(\Skydrop\Order\Create::call($order));

        \VCR\VCR::eject();
        \VCR\VCR::turnOff();
    }

    public function testCallReturnFail()
    {
        \VCR\VCR::insertCassette('create_order_fail');

        $orderHelper = new \OrdersHelper();
        $order = $orderHelper->getInvalidOrder();
        $this->assertNotTrue(\Skydrop\Order\Create::call($order));

        \VCR\VCR::eject();
        \VCR\VCR::turnOff();
    }
}
