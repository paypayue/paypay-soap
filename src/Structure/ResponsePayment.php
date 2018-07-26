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

    /** @var string */
    public $validStartDate;

    /** @var string */
    public $validEndDate;

    public function __construct(
        $paymentId,
        $referenceEntity,
        $reference,
        $paymentMethodCode,
        $paymentCancelled,
        $paymentDate,
        $paymentAmount,
        $productCode = null,
        $productDesc = null,
        $validStartDate = null,
        $validEndDate = null
    ) {
        $this->paymentId = $paymentId;
        $this->referenceEntity = $referenceEntity;
        $this->reference = $reference;
        $this->paymentMethodCode = $paymentMethodCode;
        $this->paymentCancelled = $paymentCancelled;
        $this->paymentDate = $paymentDate;
        $this->paymentAmount = $paymentAmount;
        $this->productCode = $productCode; // opcional
        $this->productDesc = $productDesc; // opcional
        $this->validStartDate = $validStartDate; // opcional
        $this->validEndDate = $validEndDate; // opcional
    }
}
