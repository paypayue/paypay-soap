<?php
namespace PayPay\Structure;

class ResponseCancelPayment {

    /** @var ResponseIntegrationState */
    public $requestState;


    /** @var ResponseCancelPaymentOption[] */
    public $paymentOptionsResult;
}
