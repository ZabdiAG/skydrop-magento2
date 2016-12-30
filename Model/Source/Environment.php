<?php

namespace Skydrop\Shipping\Model\Source;

class Environment implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs, eg. value => label
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            'staging' => 'Staging',
            'production' => 'Production',
        ];
    }
}
