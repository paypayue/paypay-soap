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

    /** @var PaymentFee */
    public $paymentFee;

    /** @var TransferDetails */
    public $transferDetails;

    /** @var PaymentRefund[] */
    public $paymentRefunds;
}
