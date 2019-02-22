<?php
namespace PayPay\Structure;

class ResponseCreditCardPayment
{
    /** @var ResponseIntegrationState */
    public $requestState;

    /** @var String */
    public $url;

    /** @var String */
    public $token;

    /** @var Int */
    public $idTransaction;
}
