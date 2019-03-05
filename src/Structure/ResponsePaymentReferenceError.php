<?php
namespace PayPay\Structure;

class ResponsePaymentReferenceError
{
    /** @var string */
    public $errorCode;

    /** @var string */
    public $errorMessage;

    /** @var string */
    public $referenceEntity;

    /** @var string */
    public $reference;
}
