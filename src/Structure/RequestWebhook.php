<?php
namespace PayPay\Structure;

class RequestWebhook
{
    const PAYMENT_CONFIRMED = 'payment_confirmed';

    /** @var string */
    public $action;

    /** @var string */
    public $url;

    public function __construct($requestData)
    {
        $this->action = $requestData['action'];
        $this->url    = $requestData['url'];
    }
}
