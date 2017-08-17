<?php
ini_set('soap.wsdl_cache_enabled', 0);
ini_set("default_socket_timeout", 5);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define("PAYPAY_WEBSERVICE_URL", "http://10.11.15.59/wilmer/paypay/paypayservices/paypayservices_c");

require 'vendor/autoload.php';

$config = \PayPay\Configuration::fromArray(
    array(
        'environment'  => 'development',
        'privateKey'   => 'DyLyeIKkclr8nl2hyW2e06j8',
        'clientId'     => '502056800',
        'platformCode' => '0003',
        'langCode'     => 'PT'
    )
);

$client = \PayPay\PayPayWebservice::init($config);


$requestReference = new \PayPay\Structure\RequestReferenceDetails(
    array(
        'amount'      => 1000,
        'productCode' => 'REF. 123'
    )
);

try {
    $response = $client->createPaymentReference($requestReference);
} catch (Exception $e) {
    $response = $e;
}

var_dump($response);

try {
    $order = new \PayPay\Structure\RequestPaymentOrder(
        array(
            'amount'      => 1000,
            'productCode' => '123',
            'productDesc' => 'DESC'
        )
    );
    $requestPayment = new \PayPay\Structure\RequestCreditCardPayment(
        $order,
        'www.paypay.pt',
        'www.paypay.pt',
        \PayPay\Structure\RequestCreditCardPayment::METHOD_MB_WAY
    );

    $response = $client->doWebPayment($requestPayment);
} catch (Exception $e) {
    $response = $e;
}

var_dump($response);
