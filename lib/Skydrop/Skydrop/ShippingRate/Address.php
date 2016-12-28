<?php

namespace Skydrop\ShippingRate;

class Address
{
    use \Skydrop\Traits\GettersAndSettersTrait;

    private $name;
    private $phone;
    private $address1;
    private $address2;
    private $city;
    private $province;
    private $postal_code;
    private $country;

    public function __construct($args=[])
    {
        $this->name        = $this->getFromArray('name', $args, '');
        $this->phone       = $this->getFromArray('phone', $args, '');
        $this->address1    = $this->getFromArray('address1', $args, '');
        $this->address2    = $this->getFromArray('address2', $args, '');
        $this->city        = $this->getFromArray('city', $args, '');
        $this->province    = $this->getFromArray('province', $args, '');
        $this->postal_code = $this->getFromArray('postal_code', $args, '');
        $this->country     = $this->getFromArray('country', $args, '');
        $this->lat         = $this->getFromArray('lat', $args, '');
        $this->lng         = $this->getFromArray('lng', $args, '');
    }

    public function toHash($rootKey)
    {
        return array(
            "{$rootKey}" => array(
                'name'        => $this->name,
                'phone'       => $this->phone,
                'address1'    => $this->address1,
                'address2'    => $this->address2,
                'city'        => $this->city,
                'province'    => $this->province,
                'postal_code' => $this->postal_code,
                'country'     => $this->country,
                'lat'         => $this->lat,
                'lng'         => $this->lng
            )
        );
    }
}
