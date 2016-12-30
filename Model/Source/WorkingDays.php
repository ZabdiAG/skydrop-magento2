<?php

namespace Skydrop\Shipping\Model\Source;

class WorkingDays implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs, eg. value => label
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [ 'value' => 0, 'label' => 'Sunday' ],
            [ 'value' => 1, 'label' => 'Monday' ],
            [ 'value' => 2, 'label' => 'Tuesday' ],
            [ 'value' => 3, 'label' => 'Wednesday' ],
            [ 'value' => 4, 'label' => 'Thursday' ],
            [ 'value' => 5, 'label' => 'Friday' ],
            [ 'value' => 6, 'label' => 'Saturday' ]
        ];
    }
}
