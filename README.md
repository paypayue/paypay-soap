# PayPay PHP SOAP Library 

Official library for PayPay SOAP API integrations written in PHP.

### Requirements
* The library requires PHP version 5.4.0 or higher and the [SOAP extension](http://php.net/manual/en/book.soap.php).

### Installation
Get started with our PHP library by hitting the download link below.

* [Download PHP Library](https://github.com/paypayue/paypay-soap-php/archive/master.zip)

### Install with composer
[Composer](https://getcomposer.org/) is a package manager for PHP. In the composer.json file in your project add:

```sh
"require" : {
    "paypayue/paypay-soap-php" : "dev-master"
}
```
Then run the following command:
```sh
$ composer install
```
Configure the environment with your platform credentials, for example:

```php
\PayPay\Configuration::setup(
    array(
        'environment'  => 'testing', // or production
        'platformCode' => '0004',
        'privateKey'   => 'Y1JgnTGN2lMOz8OXLs0s',
        'clientId'     => '503129445', // usually the client NIF
        'langCode'     => 'PT'
    )
);
```
