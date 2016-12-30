<?php

namespace Skydrop;

require_once 'Tools/ArrayTools.php';

abstract class Configs
{
    const PRODUCTION_URL = 'http://www.skydrop.com.mx/api/v2';
    const STAGING_URL = 'http://54.191.139.107/api/v2';
    public static $apiKey;
    public static $env                = 'production';
    public static $slackHook          = 'https://hooks.slack.com/services/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';
    public static $skydropOpeningTime = '10:00';
    public static $shopServiceTime    = [];
    public static $filters            = [];
    public static $modifiers          = [];
    public static $rules              = [];
    public static $workingDays        = [];
    public static $openingTime        = [];
    public static $closingTime        = [];

    public static function setApiKey($newApiKey)
    {
        self::$apiKey = $newApiKey;
    }

    public static function setEnv($newEnv)
    {
        self::$env = $newEnv;
    }

    public static function baseURL()
    {
        if (self::$env == 'production') {
            return self::PRODUCTION_URL;
        }

        return self::STAGING_URL;
    }

    public static function setFilters($filters)
    {
        self::$filters = $filters;
    }

    public static function setModifiers($modifiers)
    {
        self::$modifiers = $modifiers;
    }

    public static function setRules($rules)
    {
        self::$rules = $rules;
    }

    public static function setWorkingDays($wdays)
    {
        self::$workingDays = $wdays;
    }

    public static function setOpeningTime($time)
    {
        self::$openingTime = $time;
    }

    public static function setClosingTime($time)
    {
        self::$closingTime = $time;
    }

    public static function getDefaultFilters()
    {
        return [
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Filter\\VehicleType',
                'options' => [ 'vehicleTypes' => ['car'] ]
            ),
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Filter\\OnePerService',
                'options' => [ 'serviceTypes' => ['Hoy', 'EExps', 'next_day'] ]
            ),
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Filter\\SameDay',
                'options' => []
            ),
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Filter\\NextDay',
                'options' => []
            ),
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Filter\\Express',
                'options' => []
            ),
        ];
    }

    public static function getDefaultModifiers()
    {
        return [
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Modifier\\ServiceName',
                'options' => []
            ),
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Modifier\\CodeEncoder',
                'options' => []
            )
        ];
    }

    public static function getShopServiceTime()
    {
        if (!self::$shopServiceTime) {
            self::$shopServiceTime = new \Skydrop\ShippingRate\Service\ShopServiceTime(
                [
                    'workingDays' => self::$workingDays,
                    'openingTime' => self::$openingTime,
                    'closingTime' => self::$closingTime
                ]
            );
        }
        return self::$shopServiceTime;
    }

    public static function getFilters()
    {
        return mergeArrayByKey(
            self::getDefaultFilters(), self::$filters, 'klass'
        );
    }

    public static function getModifiers()
    {
        return mergeArrayByKey(
            self::getDefaultModifiers(), self::$modifiers, 'klass'
        );
    }

    public static function notifyErrbit($e, $params = array())
    {
        \Errbit\Errbit::instance()
            ->configure(array(
                'api_key' => 'b63effbcb994cd467a7bc94771b5096f',
                'host'    => 'errbit-skydrop.herokuapp.com'
            ))
            ->start();
        if (empty($params)) {
            \Errbit\Errbit::instance()->notify($e);
        } else {
            \Errbit\Errbit::instance()->notify(
                $e,
                ['parameters' => $params]
            );
        }
    }

    public static function notifySlack($message)
    {
        $settings = [
            'username' => 'Skydrop PHP SDK',
            'channel' => '#pedidos-fallidos',
            'link_names' => true
        ];

        $client = new \Maknz\Slack\Client(self::$slackHook, $settings);
        $client->send($message);
    }
}
