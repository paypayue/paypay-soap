<?php

namespace PayPay\Exception;

/**
 * Raised when the Braintree library is not completely configured.
 *
 * @package    PayPay
 * @subpackage Exception
 */
final class CheckWebPayment extends \Exception
{
    public function __construct(ResponsePaymentDetails $rgp)
    {
        parent::__construct($rgp->requestState->message, $rgp->requestState->code);
    }
}
