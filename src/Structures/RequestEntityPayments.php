<?php
namespace PayPay\Structures;

class RequestEntityPayments
{
    /** @var RequestReferenceDetails[] */
    public $payments;

    public function __construct()
    {
        $this->payments = array();
    }

}
