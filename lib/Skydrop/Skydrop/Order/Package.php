<?php

namespace Skydrop\Order;

class Package
{
    use \Skydrop\Traits\GettersAndSettersTrait;

    private $codAmount;

    public function __construct($args=[])
    {
        $this->codAmount = $this->getFromArray('codAmount', $args, '');
    }

    public function toHash()
    {
        return array(
            'package' => array(
                'cash_on_delivery' => 'true',
                'cod_amount' => "{$this->codAmount}",
            )
        );
    }
}
