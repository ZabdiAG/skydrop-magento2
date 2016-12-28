<?php

namespace Skydrop\ShippingRate;

class Item
{
    use \Skydrop\Traits\GettersAndSettersTrait;

    private $name;
    private $sku;
    private $quantity;
    private $price;

    public function __construct($args=[])
    {
        $this->name     = $this->getFromArray('name', $args, '');
        $this->sku      = $this->getFromArray('sku', $args, '');
        $this->quantity = $this->getFromArray('quantity', $args, '');
        $this->price    = $this->getFromArray('price', $args, '');
    }
}
