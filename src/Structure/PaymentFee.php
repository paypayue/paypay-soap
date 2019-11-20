<?php

namespace PayPay\Structure;

final class PaymentFee {
    /**
     * The net amount of the fee.
     *
     * @var Float
     */
    public $netAmount;

    /**
     * The tax amount of the fee.
     *
     * @var Float
     */
    public $taxAmount;

    /**
     * The fee invoice information
     *
     * @var InvoiceDetails
     */
    public $invoiceDetails;
}
