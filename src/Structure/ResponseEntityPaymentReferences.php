<?php
namespace PayPay\Structure;

class ResponseEntityPaymentReferences
{
    /** @var ResponseIntegrationState */
    public $integrationState;

    /** @var ResponsePaymentReferenceError[] */
    public $paymentReferenceErrors;
}
