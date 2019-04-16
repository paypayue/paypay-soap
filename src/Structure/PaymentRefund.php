<?php

namespace PayPay\Structure;

final class PaymentRefund {
    /** @var int */
    public $paymentId;

    /** @var int */
    public $amount;

    /** @var string */
    public $refundDate;
}
