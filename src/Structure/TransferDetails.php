<?php

namespace PayPay\Structure;

final class TransferDetails {
    /**
     * The bank transfer payer reference. Ex: 1-11-111
     *
     * @var String
     */
    public $payerReference;

    /**
     * The bank transfer date in ISO 8601 format.
     *
     * @var String
     */
    public $transferDate;

    /**
     * The bank transfer account IBAN.
     *
     * @var String
     */
    public $bankAccountIban;
}
