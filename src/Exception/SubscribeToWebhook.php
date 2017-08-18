<?php

namespace PayPay\Exception;

/**
 * Raised when Webhook request is invalid or incomplete.
 *
 * @package    PayPay
 * @subpackage Exception
 * @see        Configuration
 */
final class SubscribeToWebhook extends \Exception
{
    const INVALID_ACTION = '0051';
    const INVALID_URL    = '0052';

    protected $code = '';                        // user defined exception code

    public static $ERROR_CODES = array(
        self::INVALID_ACTION,
        self::INVALID_URL
    );
}
