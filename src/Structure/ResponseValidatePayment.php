<?php
namespace PayPay\Structure;

class ResponseValidatePayment
{
    /** @var ResponseIntegrationState */
    public $integrationState;

    /** @var ResponsePaymentOption[] */
    public $paymentOptions;

    /** @var string */
    public $err_msg;

    /** @var string */
    public $err_code;
}
