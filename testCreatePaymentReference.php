<?php
define('WSDL', 'https://paypay.acin.pt/paypaybeta/paypayservices/paypayservices_c/wsdl');
define('SERVER', 'https://paypay.acin.pt/paypaybeta/paypayservices/paypayservices_c/server');
define('ENCRIPTION_KEY', 'Y1JgnTGN2lMOz8OXLs0s');
define('PLATFORM_CODE', '0004');
define('NIF', '503129445');
define('CUR_DATE', '07-07-2018 09:36:03');
define('HASH', '6959c19fcfbd9b254c182b3c63e2ff89e72642b2c1743238faaa7d097bdf1e39');
define('LANG', 'PT');

require 'vendor/autoload.php';

$requestEntity = new \PayPay\Structures\RequestEntity(PLATFORM_CODE, HASH, CUR_DATE, NIF, LANG);

$client = new \SoapClient(WSDL, ['soap_version' => SOAP_1_2, 'location' => SERVER]);

$requestPayment = new PayPay\Structures\RequestReferenceDetails(
    array(
        'amount' => 1000,
        'productCode' => 'REF. 123'
    )
);
try {
    $response = $client->createPaymentReference($requestEntity, $requestPayment);
} catch (Exception $e) {
    $response = $e;
}

var_dump($response);

