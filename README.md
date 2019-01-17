# PayPay PHP SOAP Library 

Official library for PayPay SOAP API integrations written in PHP.

### Requirements
* The library requires PHP version 5.4.0 or higher and the [SOAP extension](http://php.net/manual/en/book.soap.php).

### Installation
We recommend that you use [Composer](https://getcomposer.org/) a package manager for PHP. 
In the composer.json file in your project add:

```javascript
"require" : {
    "paypayue/paypay-soap-php" : "^1.0"
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


## Getting started

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

$client = \PayPay\PayPayWebservice::init($config);
try {
    $response = $client->checkIntegrationState();
} catch (Exception $e) {
    // if something is not right an exception will be thrown
    $response = $e;
}

```

## Creating a payment reference
Use this method to quickly obtain a payment reference that you can send to your customer. 
```php
$requestReference = new \PayPay\Structure\RequestReferenceDetails(
    array(
        'amount'      => 1000,
        'productCode' => 'REF123', // Optional
        'productDesc' => 'Product description', // Optional
        'validStartDate' => '2049-06-27T00:00:00-03:00', // Optional
        'validEndDate' => '2050-06-27T23:59:59-03:00' // Optional                
    )
);
```
(Optional) Specify the payment options your customer may use to pay. Otherwise we will use the options configured on your PayPay account.

```php
$requestReference->withPaymentOptions(
    [
        \PayPay\Structure\RequestPaymentOption::MULTIBANCO(\PayPay\Structure\PaymentMethodType::NORMAL), // Check PaymentMethodType
        \PayPay\Structure\RequestPaymentOption::MBWAY(),
        \PayPay\Structure\RequestPaymentOption::CREDIT_CARD()
    ]
);

try {
    $response = $client->createPaymentReference($requestReference);
} catch (Exception $e) {
    $response = $e;
}
var_dump($response);
```
Refer to the following files for allowed parameters:
* [Codes](src/Structure/PaymentMethodCode.php)
* [Types](src/Structure/PaymentMethodType.php)

## Payment with redirect
This method is recommended for instances where the payment is made straight away, such as during a checkout process.  

```php
try {
    $order = new \PayPay\Structure\RequestPaymentOrder(
        array(
            'amount'      => 1000,
            'productCode' => 'REF123', // Optional 
            'productDesc' => 'Product description', // Optional
            'validStartDate' => '2049-06-27T00:00:00-03:00', // Optional
            'validEndDate' => '2050-06-27T23:59:59-03:00' // Optional            
        )
    );
    $requestPayment = new \PayPay\Structure\RequestCreditCardPayment(
        $order,
        'http://www.your_store_url.com/return', // Optional 
        'http://www.your_store_url.com/cancel' // Optional 
    );

    // Optional: Specify the payment options, default is credit card.
    $requestPayment->withMethods(
        array(
            \PayPay\Structure\PaymentMethodCode::CREDIT_CARD,
            \PayPay\Structure\PaymentMethodCode::MULTIBANCO,
            \PayPay\Structure\PaymentMethodCode::MBWAY
        )
    );
    
    // Optional: If you choose to send the customer info we can email them the payment receipt
    $buyer = new \PayPay\Structure\RequestBuyerInfo(
        array(
            'firstName' => 'Manuel',
            'lastName' => 'Abreu',
            'email' => 'teste@teste.pt',
            'customerId' => '123'
        )
    );

    $requestPayment->withBuyer($buyer);

    $response = $client->doWebPayment($requestPayment);
    // save $response->token and $response->idTransaction
    // redirect to $response->url
} catch (Exception $e) {
    $response = $e;
}

var_dump($response);
```

## Process incoming payments by Webhook
Use the following method to supply a url that will process all successful payments.
```php
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

PayPay will make an HTTP request to your url with POST data of the payments that were confirmed.
You can use the following code to jump start your payment processing.
```php
try {
    $webhook = \PayPay\WebhookHandler::fromPost($config);
    $webhook->eachPayment(function($payment) {
        var_dump($payment); // loop the payments
    });
    http_response_code(200); // always return an HTTP status code.
} catch (\PayPay\Exception\Webhook $e) {
    http_response_code($e->getCode());
    echo $e->getMessage();
}
```
### Additional Notes
* PayPay expects a ```HTTP 200 OK``` header in response to the webhook request. This signals that the payments we're received successfully. Otherwise, we will retry calling your url 3 times within 30 minute intervals. 
* Since PayPay may have to do repeat requests, as a failsafe you should check that each payment is not already processed on your side.
* The request has a 30s timeout so it's not recommended that you do any "heavy lifting" (eg. sending emails, slow queries, etc.) during this process. 

## Documentation
* [Official Documentation](https://paypay.pt/paypay/public/api/PAYPAY_guia_integracao.pdf) (portuguese)
