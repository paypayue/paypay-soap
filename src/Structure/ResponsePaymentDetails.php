<?php
namespace PayPay\Structure;

class ResponsePaymentDetails
{
    /** @var ResponseIntegrationState */
    public $requestState;

    /** @var Int */
    public $paid;

    /** @var String */
    public $paymentDate;
}
