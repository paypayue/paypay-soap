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

    public function __construct(
        $errorCode,
        $errorMessage,
        $referenceEntity,
        $reference
    )
    {
        $this->referenceEntity = $referenceEntity;
        $this->reference       = $reference;
        $this->errorCode       = $errorCode;
        $this->errorMessage    = $errorMessage;
    }
}
