<?php
namespace PayPay\Structure;

class RequestWebhook
{
    const PAYMENT_CONFIRMED = 'payment_confirmed';
    const PAYMENT_EXPIRED   = 'payment_expired';
    const PAYMENT_CANCELLED = 'payment_cancelled';
    const PAYMENT_REFUNDED = 'payment_refunded';

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
