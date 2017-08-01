<?php
namespace PayPay\Structures;

class ResponseWebhook
{
    /** @var ResponseIntegrationState */
    public $integrationState;

    public function __construct(ResponseIntegrationState $state)
    {
        $this->integrationState = $state;
    }
}
