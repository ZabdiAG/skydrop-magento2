<?php

namespace Skydrop\Order;

class Address
{
    use \Skydrop\Traits\GettersAndSettersTrait;

    private $name;
    private $email;
    private $streetNameAndNumber;
    private $municipality;
    private $neighborhood;
    private $telephone;

    public function __construct($args=[])
    {
        $this->name         = $this->getFromArray('name', $args, '');
        $this->email        = $this->getFromArray('email', $args, '');
        $this->streetNameAndNumber = $this->getFromArray('streetNameAndNumber', $args, '');
        $this->municipality = $this->getFromArray('municipality', $args, '');
        $this->neighborhood = $this->getFromArray('neighborhood', $args, '');
        $this->telephone    = $this->getFromArray('telephone', $args, '');
        $this->lat          = $this->getFromArray('lat', $args, '');
        $this->lng          = $this->getFromArray('lng', $args, '');
    }

    public function toHash($rootKey)
    {
        return array(
            "{$rootKey}" => array(
                'name'                   => $this->name,
                'email'                  => $this->email,
                'street_name_and_number' => $this->streetNameAndNumber,
                'municipality'           => $this->municipality,
                'neighborhood'           => $this->neighborhood,
                'telephone'              => $this->telephone,
                'lat'                    => $this->lat,
                'lng'                    => $this->lng
            )
        );
    }
}
