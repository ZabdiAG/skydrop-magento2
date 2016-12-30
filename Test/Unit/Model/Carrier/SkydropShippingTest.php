<?php
/**
 * Created by PhpStorm.
 * User: zabdi
 * Date: 12/29/16
 * Time: 3:59 PM
 */

namespace Skydrop\Shipping\Test\Unit\Model\Carrier;


use Skydrop\Shipping\Model\Carrier\SkydropShipping;


class SkydropShippingTest extends \PHPUnit_Framework_TestCase
{

  protected $model;

  protected $scopeConfig;

  protected $rateResultFactory;

  protected $rateResult;

  protected $rateMethodFactory;

  protected $rateResultMethod;

  protected $shippingOrigin;

  protected $shippingDestination;

  /**
   * @inheritdoc
   */
  protected function setUp(){
    $this->scopeConfig = $this->getMockBuilder('Magento\Framework\App\Config\ScopeConfigInterface')
      ->getMockForAbstractClass();
    $rateErrorFactory = $this->getMockBuilder('Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory')
      ->disableOriginalConstructor()
      ->setMethods(['create'])
      ->getMock();
    $logger = $this->getMockBuilder('Psr\Log\LoggerInterface')
      ->getMockForAbstractClass();
    $this->rateResultFactory = $this->getMockBuilder('Magento\Shipping\Model\Rate\ResultFactory')
      ->disableOriginalConstructor()
      ->setMethods(['create'])
      ->getMock();
    $this->rateMethodFactory = $this->getMockBuilder('Magento\Quote\Model\Quote\Address\RateResult\MethodFactory')
      ->disableOriginalConstructor()
      ->setMethods(['create'])
      ->getMock();

    //testing
    $storeManager = $this->getMockBuilder('Magento\Store\Model\StoreManagerInterface')
      ->getMockForAbstractClass();
    $customerSession= $this->getMockBuilder('Magento\Customer\Model\Session')
      ->getMockForAbstractClass();
    $customerAddress= $this->getMockBuilder('Magento\Customer\Model\Address')
      ->getMockForAbstractClass();
    $configSDK = $this->getMockBuilder('Skydrop\Shipping\Helper\SDK\config')
      ->getMock();
    $carrierHelper = $this->getMockBuilder('Skydrop\Shipping\Helper\Carrier')
      ->getMock();
    $sdkHelper = $this->getMockBuilder('SKydrop\Shipping\Helper\SDK\SkydropSDK')
      ->getMock();
    //end testing
    $this->rateResultMethod = $this->getMockBuilder('Magento\Quote\Model\Quote\Address\RateResult\Method')
      ->disableOriginalConstructor()
      ->getMock();

    $this->rateResult = $this->getMockBuilder('Magento\Shipping\Model\Rate\Result')
      ->disableOriginalConstructor()
      ->getMock();

    $this->model = new SkydropShipping(
      $this->scopeConfig
      ,$rateErrorFactory
      ,$logger
      ,$this->rateResultFactory
      ,$this->rateMethodFactory
      ,$storeManager
      ,$customerSession
      ,$customerAddress
      ,$configSDK
      ,$carrierHelper
      ,$sdkHelper
    );

  }

  public function testAssertTruefirstTest(){
    $this->assertTrue(true,'here i am');
  }

}
