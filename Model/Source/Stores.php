<?php

namespace Skydrop\Shipping\Model\Source;

class Stores implements \Magento\Framework\Option\ArrayInterface
{
  /**
  * Return array of options as value-label pairs, eg. value => label
  *
  * @return array
  */
  public function toOptionArray()
  {
    /** @var \Magento\Framework\App\ObjectManager $om */
    $om = \Magento\Framework\App\ObjectManager::getInstance();
    /** @var \Magento\Store\Model\StoreManagerInterface|\Magento\Store\Model\StoreManager $storeManager */
    $storeManager = $om->get('Magento\Store\Model\StoreManagerInterface');

    $options = [];
    foreach ($storeManager->getStores($withDefault = false) as $store) {
      if (!$withDefault && $store->getId() == 0) {
        continue;
      }
      $options[] = [
        'value' => $store->getId(), 'label' => $store->getName()
      ];
    }
    return $options;
  }
}
