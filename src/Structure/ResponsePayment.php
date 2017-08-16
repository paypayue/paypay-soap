<?php
namespace PayPay\Structure;

class ResponsePayment
{
    /** @var int */
    public $paymentId;

    /** @var string */
    public $referenceEntity;

    /** @var string */
    public $reference;

    /** @var string */
    public $paymentMethodCode;

    /** @var boolean */
    public $paymentCancelled;

    /** @var string */
    public $paymentDate;

    /** @var int */
    public $paymentAmount;

    /** @var string */
    public $productCode;

    /** @var string */
    public $productDesc;

    public function __construct(
        $paymentId,
        $referenceEntity,
        $reference,
        $paymentMethodCode,
        $paymentCancelled,
        $paymentDate,
        $paymentAmount,
        $productCode,
        $productDesc
    )
    {
        $this->paymentId         = $paymentId;
        $this->referenceEntity   = $referenceEntity;
        $this->reference         = $reference;
        $this->paymentMethodCode = $paymentMethodCode;
        $this->paymentCancelled  = $paymentCancelled;
        $this->paymentDate       = $paymentDate;
        $this->paymentAmount     = $paymentAmount;
        $this->productCode       = $productCode;
        $this->productDesc       = $productDesc;
    }
}
