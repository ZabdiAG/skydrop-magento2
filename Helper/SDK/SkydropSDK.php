<?php
/**
 * Created by PhpStorm.
 * User: zabdi
 * Date: 12/28/16
 * Time: 3:16 PM
 */

namespace Skydrop\Shipping\Helper\SDK;


class SkydropSDK extends \Magento\Framework\App\Helper\AbstractHelper
{

  public function getShippingRatesBuilder($items, $origin, $destination){
    return new \Skydrop\ShippingRate\ShippingRateBuilder([
      'origin'        => $origin,
      'destination'   => $destination,
      'items'        => $items
    ]);
  }

}