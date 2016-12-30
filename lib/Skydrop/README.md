# Skydrop PHP
This is a library to easily consume the Skydrop REST API

## Installation

Require the skydrop-php in your composer.json:

```json
{
    "require": {
        "skydropx/skydrop-php": "1.0.*"
    }
}
```

And then execute:

    $ composer install

## Usage

Set your Skydrop API key

```php
Skydrop\Configs::setApiKey('your_api_key');
```
```php
Skydrop\Configs::setWorkingDays([1,2,3,4,5]); // Monday to Friday.
Skydrop\Configs::setOpeningTime([ 'hour' => 9, 'min' => 30]); // Your shop opening time.
Skydrop\Configs::setClosingTime([ 'hour' => 21, 'min' => 30]); // Your shop closin time.

$filters = [
  (object)array(
      'klass' => '\\Skydrop\\ShippingRate\\Filter\\OnePerService',
      'options' => [ 'serviceTypes' => ['Hoy', 'next_day', 'EExps'] ]
      ),
  (object)array(
      'klass' => '\\Skydrop\\ShippingRate\\Filter\\VehicleType',
      'options' => [ 'vehicleTypes' => ['scooter', 'car', 'van'] ]
      )
];
Skydrop\Configs::setFilters($filters);

$modifiers = [
  (object)array(
      'klass' => '\\Skydrop\\ShippingRate\\Modifier\\ServiceName',
      'options' => [ 'serviceNames' => [
          'Hoy' => 'your custom message',
          'next_day' => 'your custom message',
          'EExps' => 'your custom message'
      ] ]
      )
];
Skydrop\Configs::setModifier($modifiers);

// Use any of the resources
$shippingRate = new Skydrop\ShippingRates\Search($items, $rates);
$shippingRate->all();
```

## Testing

to run testing, install php-timecop:
```
https://github.com/hnw/php-timecop
```

License
-------
Developed by [Skydrop](http://www.skydrop.com.mx). Available with [MIT License](LICENSE).

We are hiring
-------------

If you are a comfortable working with a range of backend languages (Ruby, Node.js, Vuejs, Javascript, PHP) and frameworks,
you have solid foundation in data structures, algorithms and software design with strong analytical and debugging skills.
Send your CV, github url to arnoldo@skydrop.com.mx
