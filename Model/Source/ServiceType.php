<?php

namespace Skydrop\Shipping\Model\Source;

class ServiceType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs, eg. value => label
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [ 'value' => 'Hoy',      'label' => 'Same Day' ],
        ];
    }
}

