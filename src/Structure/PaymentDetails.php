<?php

namespace PayPay\Structure;

class PaymentDetails {
    /** @var String */
    public $code;

    /** @var Int */
    public $state;

    /** @var String */
    public $message;

    /** @var int */
    public $paymentId;

    /** @var String */
    public $reference;

    /** @var Int */
    public $paymentState;

    /** @var Int */
    public $paymentStateId;

    /** @var Int */
    public $paymentBlocked;

    /** @var Int */
    public $paymentCancelled;

    /** @var String */
    public $paymentDate;

    /** @var String */
    public $paymentMode;

    /** @var Int */
    public $paymentAmount;

    /** @var PaymentFee */
    public $paymentFee;

    /** @var TransferDetails */
    public $transferDetails;

    /** @var PaymentRefund[] */
    public $paymentRefunds;
}
