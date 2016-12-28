<?php
namespace Skydrop\Shipping\Model\Carrier;

require_once(__DIR__.'/../../lib/Skydrop/vendor/autoload.php');#TODO: delete this line when installed with composer

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Address;
use Skydrop\Shipping\Helper;

/**
* Class Carrier In-Store Pickup shipping model
*/
class SkydropShipping extends AbstractCarrier implements CarrierInterface
{
  /**
  * Carrier's code
  *
  * @var string
  */
  protected $_code = 'skydrop_shipping';

  /**
  * Whether this carrier has fixed rates calculation
  *
  * @var bool
  */
  protected $_isFixed = true;

  /**
  * @var ResultFactory
  */
  protected $rateResultFactory;

  /**
  * @var MethodFactory
  */
  protected $rateMethodFactory;

  /**
   * @var StoreManagerInterface */
  protected $storeManager;

  /**
   * @var Session
   */
  public $customerSession;

  /**
   * @var Address
   */
  public $customerAddress;

  /**
   * @var SDK\Config
   */
  public $configSDK;

  public $carrierHelper;

  public $skydropSDKHelper;

  /**
  * @param ScopeConfigInterface $scopeConfig
  * @param ErrorFactory $rateErrorFactory
  * @param LoggerInterface $logger
  * @param ResultFactory $rateResultFactory
  * @param MethodFactory $rateMethodFactory
  * @param StoreManagerInterface $storeManagerInterface
  * @param array $data
  */
  public function __construct(
    ScopeConfigInterface $scopeConfig,
    ErrorFactory $rateErrorFactory,
    LoggerInterface $logger,
    ResultFactory $rateResultFactory,
    MethodFactory $rateMethodFactory,
    StoreManagerInterface $storeManager,
    Session $customerSession,
    Address $customerAddress,
    Helper\SDK\Config $configSDK,
    Helper\Carrier $carrierHelper,
    Helper\SDK\SkydropSDK $SDKHelper,
    array $data = []
  ) {
    $this->rateResultFactory = $rateResultFactory;
    $this->rateMethodFactory = $rateMethodFactory;
    $this->storeManager      = $storeManager;
    $this->customerSession   = $customerSession;
    $this->customerAddress   = $customerAddress;
    $this->configSDK         = $configSDK;
    $this->carrierHelper     = $carrierHelper;
    $this->skydropSDKHelper  = $SDKHelper;
    parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
  }

  /**
  * Generates list of allowed carrier`s shipping methods
  * Displays on cart price rules page
  *
  * @return array
  * @api
  */
  public function getAllowedMethods()
  {
    return [$this->getCarrierCode() => __($this->getConfigData('name'))];
  }

  /**
  * Collect and get rates for storefront
  *
  * @SuppressWarnings(PHPMD.UnusedFormalParameter)
  * @param RateRequest $request
  * @return DataObject|bool|null
  * @api
*/
  public function collectRates(RateRequest $request){
    $result = $this->rateResultFactory->create();
    if (!in_array($this->getCurrentStoreId(), $this->getIdStoresConfigured())) {
      return $result;
    }
    $this->configSDK->configure(
      $this->getConfigData('api_key')
      ,$this->getConfigData('environment')
    );
    $this->configSDK->filters(
      explode(',',$this->getConfigData('working_days'))
      ,explode(',',$this->getConfigData('opening_time'))
      ,explode(',',$this->getConfigData('closing_time'))
    );
    $this->configSDK->customFilters(
      $vehicleType = $this->getConfigData('vehicle_type')
      ,$serviceType =  explode(',',$this->getConfigData('service_type'))
    );
    $this->configSDK->rules();#TODO: add this functionallity
    try {
      $items =$this->carrierHelper->getItems($request);
      $builder = new \Skydrop\ShippingRate\ShippingRateBuilder([
        'origin'        => $this->carrierHelper->getOrigin( $this->storeManager->getStore(),$this ),
        'destination'   => $this->carrierHelper->getDestination($request, $this),
        'items'        => $items
      ]);
      $searcher = new \Skydrop\ShippingRate\Search($builder);
      $result = $this->addRates($searcher->call());
    } catch (\RuntimeException $e) {
      \Skydrop\Configs::notifyErrbit($e);
      return $result;
    }
    return $result;
  }

  /**
   * @return int
   */
  public function getCurrentStoreId(){
    return $this->storeManager->getStore()->getId();
  }

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

  #Dependencies: ResultFactory
  /**
   * @param $rates
   * @return \Magento\Shipping\Model\Rate\Result
   */
  public function addRates($rates){
    $result = $this->rateResultFactory->create();
    foreach ($rates as $rate) {
      $result->append($this->_getRate($rate));
    }
    return $result;
  }

  #Dependencies: $rateMethodFactory,
  /**
   * @param $skydrop_rate
   * @return \Magento\Quote\Model\Quote\Address\RateResult\Method
   */
  public function _getRate($skydrop_rate){
    $rateResultMethod = $this->rateMethodFactory->create();
    $rateResultMethod->setData('carrier', $this->getCarrierCode());
    $rateResultMethod->setData('carrier_title', $this->getConfigData('title'));

    $rateResultMethod->setData('method',$skydrop_rate->service_code);
    $rateResultMethod->setData('method_title',$skydrop_rate->service_name);

    $rateResultMethod->setPrice($skydrop_rate->total_price);
    $rateResultMethod->setData('cost',0);
    return $rateResultMethod;
  }
}
