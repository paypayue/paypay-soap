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

}
