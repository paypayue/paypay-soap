<?php
namespace PayPay\Exception;

/**
 * Raised when the CheckEntityPayments method returns an error code.
 *
 * @package    PayPay
 * @subpackage Exception
 */
final class CheckEntityPayments extends \Exception {

    public function __construct(ResponseEntityPaymentsDetails $response)
    {
        parent::__construct($response->state->message, $response->state->code);
    }
}
