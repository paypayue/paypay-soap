<?php
namespace PayPay\Structure;

class ResponseCheckWebPayment
{
    /** @var string */
    public $errorCode;

    /** @var string */
    public $errorMessage;

    /** @var string */
    public $referenceEntity;

    /** @var string */
    public $referencia;

    public function __construct(
        $errorCode,
        $errorMessage,
        $referenceEntity,
        $referencia
    )
    {
        $this->referenceEntity = $referenceEntity;
        $this->referencia      = $referencia;
        $this->errorCode       = $errorCode;
        $this->errorMessage    = $errorMessage;
    }
}
