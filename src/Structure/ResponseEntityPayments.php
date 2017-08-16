<?php
namespace PayPay\Structure;

class ResponseEntityPayments
{
    /** @var ResponseIntegrationState */
    public $integrationState;

    /** @var ResponsePayment[] */
    public $payments;

    public function __construct($state)
    {
        $this->integrationState = $state;
        $this->payments = array();
    }

}
