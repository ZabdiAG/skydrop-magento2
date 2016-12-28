<?php
/**
 * Created by PhpStorm.
 * User: zabdi
 * Date: 12/28/16
 * Time: 12:16 PM
 */

namespace Skydrop\Shipping\Helper\SDK;

require_once(__DIR__.'/../../lib/Skydrop/vendor/autoload.php');#TODO: deletee this line when installed with composer

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{

  /**
   * Configure SDK api key and environment, whether staging or production
   * @param string $api_key
   * @param string $environment
   */
  public function configure($api_key, $environment){#TODO: possibly changing how parameters are received
    \Skydrop\Configs::setApiKey($api_key);
    \Skydrop\Configs::setEnv($environment);
  }

  /**
   * Configure default Filters on SkydropSDK.
   * Those filters are: working days, opening time and closing time
   *
   * @param array $working_days
   * @param array $opening_time
   * @param array $closing_time
   */
  public function filters($working_days, $opening_time, $closing_time){
    \Skydrop\Configs::setWorkingDays($working_days);
    \Skydrop\Configs::setOpeningTime([
      'hour'    =>$opening_time[0],
      'min'     =>$opening_time[1]
    ]);
    \Skydrop\Configs::setClosingTime([
      'hour'  => $closing_time[0],
      'min'   => $closing_time[1]
    ]);
  }

  /**
   * The custom filters are: vehicle type and service type
   * @param string $vehicle_type
   * @param array $service_type ['Hoy', 'next_day', 'EExps']
   */
  public function customFilters($vehicle_type, $service_type){#TODO: possble change name
    $filters = [];
    $filters[] = (object)array(
      'klass'     => '\\Skydrop\\ShippingRate\\Filter\\VehicleType',
      'options'   => [ 'vehicleTypes' => [$vehicle_type] ]
    );
    $filters[] = (object)array(
      'klass'     =>'\\Skydrop\\ShippingRate\\Filter\\OnePerService',
      'options'   =>[ 'serviceTypes' =>$service_type ]
    );
    \Skydrop\Configs::setFilters($filters);
  }

  public function rules(){//this code will be for products tag

  }
}