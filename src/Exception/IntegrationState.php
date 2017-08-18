<?php

namespace PayPay\Exception;

/**
 * Raised when the client webservice integration data is not correct.
 *
 * @package    PayPay
 * @subpackage Exception
 */
final class IntegrationState extends \Exception
{
    public function __construct($ris)
    {
        parent::__construct($ris->message, $ris->code);
    }
}
