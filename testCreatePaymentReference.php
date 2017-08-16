<?php
ini_set('soap.wsdl_cache_enabled', 0);
ini_set("default_socket_timeout", 5);

require 'init.php';

$config = \PayPay\Configuration::fromArray(
    array(
        'environment'  => 'testing',
        'privateKey'   => 'Y1JgnTGN2lMOz8OXLs0s',
        'clientId'     => '503129445',
        'platformCode' => '0004',
        'langCode'     => 'PT'
    )
);

$client = \PayPay\PayPayWebservice::init($config);

try {
    $response = $client->createPaymentReference(
        array(
            'amount'      => 1000,
            'productCode' => 'REF. 123'
        )
    );
} catch (Exception $e) {
    $response = $e;
}

var_dump($response);

