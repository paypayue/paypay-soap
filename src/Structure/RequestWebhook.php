<?php
namespace PayPay\Structures;

class RequestWebhook
{
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
