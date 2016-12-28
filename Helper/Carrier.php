<?php
namespace Skydrop\Shipping\Helper;

use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Store\Model\StoreManagerInterface;

class Carrier extends \Magento\Framework\App\Helper\AbstractHelper
{
  /**
   * @var StoreManagerInterface
   */
  protected $storeManager;

  #Dependencies: AbstractCarrier
  /**
   * @return array
   */
  public function getIdStoresConfigured(){
    return $storesIds = explode(
      ',',
      $this->getConfigData('stores')
    );
  }

  /**
   * @return int
   */
  public function getCurrentStoreId(){
    return $this->storeManager->getStore()->getId();
  }

  #Dependencies: RateRequest
  /**
   * @param RateRequest $request
   * @return array
   */
  public function getItems($request){
    $items = [];
    foreach ($request->getAllItems() as $item) {
      $items[]['tags']=[];
    }
    return $items;
  }

  /**
   * @return \Skydrop\ShippingRate\Address
   */
  public function getOrigin($store, AbstractCarrier $carrier){//TODO: check if this works with multi-stores
    $shippingPath = 'shipping/origin';
    $origin = [
      "country"     => $store->getConfig("{$shippingPath}/country_id"),
      "postal_code" => $store->getConfig("{$shippingPath}/postcode"),
      "province"    => $store->getConfig("{$shippingPath}/region_id"),
      "city"        => $store->getConfig("{$shippingPath}/city"),
      "address1"    => $store->getConfig("{$shippingPath}/street_line1"),
      "address2"    => $store->getConfig("{$shippingPath}/street_line2"),
      "lat"         => $carrier->getConfigData('lat'),
      "lng"         => $carrier->getConfigData('lng')
    ];
    return new \Skydrop\ShippingRate\Address($origin);
  }

  /**
   * @param $request
   * @return \Skydrop\ShippingRate\Address
   */
  public function getDestination($request, AbstractCarrier $carrier){
    $destination = [
      "country"     => $request->getDestCountryId(),
      "postal_code" => $request->getDestPostcode(),
      "province"    => $request->getDestRegionCode(),
      "city"        => $request->getDestCity(),
      "address1"    => $request->getDestStreet(),
      "name"        => '',//TODO: get this data
      "phone"       => ''//TODO: get this data
    ];
    if ($destination['address1']){
      return new \Skydrop\ShippingRate\Address($destination);
    }
    $billingID =  $carrier->customerSession->getCustomer()->getDefaultShipping();
    $address = $carrier->customerAddress->load($billingID);
    $destination = [
      "country"     => $address->getData()['country_id'],
      "postal_code" => $address->getData()['postcode'],
      "province"    => $address->getData()['region'],
      "city"        => $address->getData()['city'],
      "address1"    => $address->getData()['street'],
      "name"        => $address->getData()['firstname'].' '.$address->getData()['lastname'],
      "phone"       => $address->getData()['telephone']
    ];
    return new \Skydrop\ShippingRate\Address($destination);
  }

}