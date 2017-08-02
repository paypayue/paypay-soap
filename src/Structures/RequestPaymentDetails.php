<?php
namespace PayPay\Structures;

class RequestPaymentDetails
{
    /** @var string */
    public $token;

    /** @var string */
    public $idTransaction;

    public function __construct($token, $idTransaction)
    {
        $this->token            = $token;
        $this->idTransaction    = $idTransaction;
    }
}
