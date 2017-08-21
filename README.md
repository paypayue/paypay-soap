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

Then add the following line at the beginning of your code:
```php
require 'vendor/autoload.php';
```

If you can't use Composer simply download our PHP library by hitting the link below then integrate it into your project.
* [Download PHP Library](https://github.com/paypayue/paypay-soap-php/archive/master.zip)


### Getting started

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

### Creating a payment reference
Use this method to obtain a payment reference that you can send to your customer. 
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
var_dump($response);
```


### Payment with redirect
This method is recommended for instances where the payment is made straight away, such as during a checkout process.  

```php
try {
    $order = new \PayPay\Structure\RequestPaymentOrder(
        array(
            'amount'      => 1000,
            'productCode' => 'REF123',
            'productDesc' => 'Product description'
        )
    );
    $requestPayment = new \PayPay\Structure\RequestCreditCardPayment(
        $order,
        'http://www.your_store_url.com/return',
        'http://www.your_store_url.com/cancel', /// optional 
        \PayPay\Structure\RequestCreditCardPayment::METHOD_CREDIT_CARD // optional, default is credit card
    );

    $response = $client->doWebPayment($requestPayment);
    // redirect to $response->url
} catch (Exception $e) {
    $response = $e;
}

var_dump($response);
```

### Subscribing to a webhook to receive payment confirmations 
Use the following method to supply a url that will process all successful payments.
```php
$client = \PayPay\PayPayWebservice::init($config);
$webhook = new \PayPay\Structure\RequestWebhook(
    array(
        'action' => \PayPay\Structure\RequestWebhook::PAYMENT_CONFIRMED,
        'url' => 'htt://www.your_process_url.com'
    )
);
try {
    $response = $client->subscribeToWebhook($webhook);
} catch (Exception $e) {
    $response = $e;
}

var_dump($response);
```

PayPay will call your url with POST data of the payments that were confirmed. 
You can use the following code to jump start your payment processing.
```php
try {
    $webhook = \PayPay\WebhookHandler::fromPost($config);
    $webhook->eachPayment(function($payment) {
        var_dump($payment); // process 
    });
    http_response_code(200); // always return an HTTP status code.
} catch (\PayPay\Exception\Webhook $e) {
    http_response_code($e->getCode());
    echo $e->getMessage();
}

var_dump($_POST);
```
