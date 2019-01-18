<?php
namespace PayPay\Structure;

class RequestEntityPayments
{
    /** @var RequestReferenceDetails[] */
    public $payments;

    public function __construct()
    {
        $this->payments = array();
    }

    public function addPayment(RequestReferenceDetails $payment)
    {
        $this->payments[] = $payment;
    }
}
