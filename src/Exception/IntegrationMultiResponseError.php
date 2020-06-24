<?php

namespace PayPay\Exception;

/**
 * Raised when Webhook request is invalid or incomplete with multi errors.
 *
 * @package    PayPay
 * @subpackage Exception
 * @see        Configuration
 */
final class IntegrationMultiResponseError extends \Exception
{
	private $integrationState;
	private $responseErrors;

    public function __construct(\PayPay\Structure\ResponseIntegrationState $integrationState, $responseErrors) {
        $this->integrationState = $integrationState;
        $this->responseErrors   = $responseErrors;
    }

    public function getIntegrationState()
    {
    	return $this->integrationState;
    }

    public function getResponseErrors()
    {
    	return $this->responseErrors;
    }

    public function getMultiResponseError()
    {
    	return [
            'integrationState' => $this->getIntegrationState(), 
            'responseErrors'   => $this->getResponseErrors()
        ];
    }
}