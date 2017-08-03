<?php

namespace PayPay\Exception;

/**
 * Raised when the
 *
 * @package    PayPay
 * @subpackage Exception
 */
final class IntegrationState extends \Exception {

    public function __construct($ris)
    {
        parent::__construct($ris->integrationState->message, $ris->integrationState->code);
    }
}
