<?php
namespace PayPay\Structure;

class RequestPaymentReference
{
    /** @var string */
    public $referenceEntity;

    /** @var string */
    public $reference;

    /** @var int */
    public $amount;

    /** @var string */
    public $creationDate;

    /** @var string */
    public $productCode;

    /** @var string */
    public $productDesc;

    public function __construct(
        $referenceEntity,
        $reference,
        $amount,
        $creationDate,
        $productCode,
        $productDesc
    )
    {
        $this->referenceEntity = $referenceEntity;
        $this->reference       = $reference;
        $this->amount          = $amount;
        $this->creationDate    = $creationDate;
        $this->productCode     = $productCode; // opcional
        $this->productDesc     = $productDesc; // opcional
    }
}
