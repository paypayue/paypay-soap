<?php
namespace PayPay\Structure;

class ResponseEntityPaymentsDetails {

    /** @var ResponseIntegrationState */
    public $state;

    /** @var PaymentDetails[] */
    public $payments;

    public function __construct(ResponseIntegrationState $state)
    {
        $this->state = $state;
    }

    public function addPayment(PaymentDetails $payment)
    {
        $this->payments[] = $payment;
    }
}
