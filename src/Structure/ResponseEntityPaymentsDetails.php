<?php
namespace PayPay\Structure;

class ResponseEntityPaymentsDetails {

    /** @var ResponseIntegrationState */
    public $state;

    /** @var PaymentDetails[] */
    public $payments;
}
