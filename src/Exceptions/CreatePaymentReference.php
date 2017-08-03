<?php

namespace PayPay\Exception;
/**
 * Raised when the createPaymentReference method returns an error code.
 *
 * @package    PayPay
 * @subpackage Exception
 */
final class CreatePaymentReference extends \Exception {

    public function __construct(ResponseGetPayment $rgp)
    {
        parent::__construct($rgp->err_msg, $rgp->err_code);
    }
}
