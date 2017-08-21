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
    const CODE_INVALID_CREDENTIALS                 = '0000';
    const CODE_INTEGRATION_NOT_FOUND               = '01';
    const CODE_INTEGRATION_VALID                   = '02';
    const CODE_INTEGRATION_REACHED_REFERENCE_LIMIT = '03';
    const CODE_INTEGRATION_MISSING_REFERENCE_RANGE = '04';
    const CODE_INTEGRATION_MISSING_PAYMENT_METHODS = '05';

    public static $ERROR_CODES = array(
        self::CODE_INVALID_CREDENTIALS,
        self::CODE_INTEGRATION_NOT_FOUND,
        self::CODE_INTEGRATION_VALID,
        self::CODE_INTEGRATION_REACHED_REFERENCE_LIMIT,
        self::CODE_INTEGRATION_MISSING_REFERENCE_RANGE,
        self::CODE_INTEGRATION_MISSING_PAYMENT_METHODS,
    );

    public function __construct(\PayPay\Structure\ResponseIntegrationState $ris)
    {
        parent::__construct($ris->message, $ris->code);
    }

    public static function check(\PayPay\Structure\ResponseIntegrationState $integrationState)
    {
        return $integrationState->state == 0 && in_array($integrationState->code, self::$ERROR_CODES);
    }
}
