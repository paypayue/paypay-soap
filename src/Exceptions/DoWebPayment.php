<?php

namespace PayPay\Exception;
/**
 * Raised when the doWebPayment method returns an error code.
 *
 * @package    PayPay
 * @subpackage Exception
 */
final class DoWebPayment extends \Exception {

    public function __construct(ResponseCreditCardPayment $rgp)
    {
        parent::__construct($rgp->requestState->message, $rgp->requestState->code);
    }

}

?>
