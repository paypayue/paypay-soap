# PayPay PHP SOAP Library 

Official library for PayPay SOAP API integrations written in PHP.

### Requirements
* The library requires PHP version 5.4.0 or higher and the [SOAP extension](http://php.net/manual/en/book.soap.php).

### Installation
We recommend that you use [Composer](https://getcomposer.org/) a package manager for PHP. 
In the composer.json file in your project add:

```sh
"require" : {
    "paypayue/paypay-soap-php" : "dev-master"
}
```
In your project folder run the following command:
```sh
$ composer install
```

If you can't use Composer simply download our PHP library by hitting the link below then integrate it into your project.
* [Download PHP Library](https://github.com/paypayue/paypay-soap-php/archive/master.zip)


### Getting started

Add the following line at the beginning of your code:
```php
require 'vendor/autoload.php';
```

Configure the environment with your platform credentials, or use the following for testing:

```php
$config = \PayPay\Configuration::setup(
    array(
        'environment'  => 'testing', // or production
        'platformCode' => '0004',
        'privateKey'   => 'Y1JgnTGN2lMOz8OXLs0s',
        'clientId'     => '503129445', // usually the client NIF
        'langCode'     => 'PT'
    )
);
```

### Create a payment reference
```php
$client = \PayPay\PayPayWebservice::init($config);


$requestReference = new \PayPay\Structure\RequestReferenceDetails(
    array(
        'amount'      => 1000,
        'productCode' => 'REF123',
        'productDesc' => 'Product description'
    )
);

try {
    $response = $client->createPaymentReference($requestReference);
} catch (Exception $e) {
    $response = $e;
}
```
