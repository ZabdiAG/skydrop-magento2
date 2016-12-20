<?php

namespace Skydrop\Shipping\Model\Source;

class ProductsWithTagRules implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs, eg. value => label
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [ 'value' => '', 'label' => 'Disabled' ],
            [ 'value' => 'every', 'label' => 'All' ],
            [ 'value' => 'once',  'label' => 'Anyone' ],
        ];
    }
}
