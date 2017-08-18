<?php
ini_set('soap.wsdl_cache_enabled', 0);
ini_set("default_socket_timeout", 5);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

try {
    $webhook = \PayPay\WebhookHandler::fromPost($config);
    $webhook->eachPayment(function($payment) {
        var_dump($payment);
    });
    http_response_code(200);
} catch (\PayPay\Exception\Webhook $e) {
    http_response_code($e->getCode());
    echo $e->getMessage();
}

var_dump($_POST);

/*{"hookAction":"payment_confirmed","hookDate":"11-08-2017 09:35:27","hookHash":"8948192d2e47a8a3f6564d183b416d949d2b8931becbfbfd80b8a98385ab539f","payments":[{"paymentId":"11222","referenceEntity":"12797","reference":"000992146","paymentMethodCode":"CC","paymentDate":"11-08-2017 09:35:00","paymentAmount":"5000","productCode":null,"productDesc":null}, {"paymentId":"11223","referenceEntity":"12797","reference":"000992146","paymentMethodCode":"CC","paymentDate":"11-08-2017 09:35:00","paymentAmount":"5000","productCode":null,"productDesc":null}]}*/
