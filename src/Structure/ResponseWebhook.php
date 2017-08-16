<?php
namespace PayPay\Structure;

class ResponseWebhook
{
    /** @var ResponseIntegrationState */
    public $integrationState;

    public function __construct(ResponseIntegrationState $state)
    {
        $this->integrationState = $state;
    }
}
