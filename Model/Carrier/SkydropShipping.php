<?php
namespace Skydrop\Shipping\Model\Carrier;

require_once(__DIR__.'/../../lib/Skydrop/vendor/autoload.php');#TODO: delete this line when installed with composer

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Psr\Log\LoggerInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Address;
use Skydrop\Shipping\Helper;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Framework\DataObject;



/**
* Class Carrier In-Store Pickup shipping model
*/
class SkydropShipping extends \Magento\Shipping\Model\Carrier\AbstractCarrier
  implements \Magento\Shipping\Model\Carrier\CarrierInterface
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
   * @var Helper\SDK\Config
   */
  public $configSDK;

  /**
   * @var Helper\Carrier
   */
  public $carrierHelper;

  /**
   * @var Helper\SDK\SkydropSDK
   */
  public $skydropSDKHelper;


  /**
   * SkydropShipping constructor.
   * @param ScopeConfigInterface $scopeConfig
   * @param ErrorFactory $rateErrorFactory
   * @param LoggerInterface $logger
   * @param ResultFactory $rateResultFactory
   * @param MethodFactory $rateMethodFactory
   * @param StoreManagerInterface $storeManager
   * @param Session $customerSession
   * @param Address $customerAddress
   * @param Helper\SDK\Config $configSDK
   * @param Helper\Carrier $carrierHelper
   * @param Helper\SDK\SkydropSDK $SDKHelper
   * @param array $data
   */
  public function __construct(
    ScopeConfigInterface $scopeConfig
    ,ErrorFactory $rateErrorFactory
    ,LoggerInterface $logger
    ,ResultFactory $rateResultFactory
    ,MethodFactory $rateMethodFactory
    ,StoreManagerInterface $storeManager
    ,Session $customerSession
    ,Address $customerAddress
    ,Helper\SDK\Config $configSDK
    ,Helper\Carrier $carrierHelper
    ,Helper\SDK\SkydropSDK $SDKHelper
    ,array $data = []
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
  * @return \Magento\Shipping\Model\Rate\Result
   * @api
*/
  public function collectRates(RateRequest $request){
    $result = $this->rateResultFactory->create();
    if (!in_array(
      $this->carrierHelper->getCurrentStoreId($this->storeManager),
      $this->carrierHelper->getIdStoresConfigured($this)
    )) {
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
