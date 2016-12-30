<?php

class OrdersHelper
{
    public function getValidOrder()
    {
        return new \Skydrop\Order\OrderBuilder(
            [
                'pickup'   => $this->getValidAddress(),
                'delivery' => $this->getValidAddress(),
                'service'  => $this->getValidService(),
                'package'  => $this->getValidPackage(),
            ]
        );
    }

    public function getInvalidOrder()
    {
        return new \Skydrop\Order\OrderBuilder(
            [
                'pickup'   => $this->getInvalidAddress(),
                'delivery' => $this->getInvalidAddress(),
                'service'  => $this->getInvalidService(),
                'package'  => $this->getInvalidPackage(),
            ]
        );
    }

    private function getValidService()
    {
        return new \Skydrop\Order\Service(
            [
                'serviceCode'  => 'Hoy',
                'vehicleType'  => 'car',
                'scheduleDate' => '2016-10-10',
                'startingHour' => '10:00',
                'endingHour'   => '22:00'
            ]
        );
    }

    private function getValidPackage()
    {
        return new \Skydrop\Order\Package(
            [
                'package' => [
                    'cash_on_delivery' => 'true',
                    'cod_amount' => '100',
                ],
            ]
        );
    }

    private function getValidAddress()
    {
        return new \Skydrop\Order\Address(
            [
                'name' => 'juan pablo',
                'email' => 'pjuan@gmail.com',
                'streetNameAndNumber' => 'rio guadalquivir 422',
                'municipality' => 'san pedro',
                'neighborhood' => 'del valle',
                'telephone' => '81818181818',
            ]
        );
    }

    private function getInvalidAddress()
    {
        return new \Skydrop\Order\Address([]);
    }

    private function getInvalidService()
    {
        return new \Skydrop\Order\Service([]);
    }

    private function getInvalidPackage()
    {
        return new \Skydrop\Order\Package([]);
    }
}
