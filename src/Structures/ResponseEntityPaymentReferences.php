<?php
namespace PayPay\Structures;

class ResponseEntityPaymentReferences
{
    /** @var ResponseIntegrationState */
    public $integrationState;

    /** @var ResponsePaymentReferenceError[] */
    public $paymentReferenceErrors;

    public function __construct(ResponseIntegrationState $state)
    {
        $this->integrationState = $state;
        $this->paymentReferenceErrors = array();
    }

}
