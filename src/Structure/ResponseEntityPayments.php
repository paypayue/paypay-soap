<?php
namespace PayPay\Structure;

class ResponseEntityPayments
{
    /** @var ResponseIntegrationState */
    public $integrationState;

    /** @var ResponsePayment[] */
    public $payments;
}
