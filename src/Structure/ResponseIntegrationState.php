<?php
namespace PayPay\Structure;

class ResponseIntegrationState
{
    /** @var string */
    public $state;
    /** @var string */
    public $code;
    /** @var string */
    public $message;

    public function __construct($params)
    {
        $this->code    = $params['code'];
        $this->message = $params['message'];
        $this->state   = $params['state'];
    }

}
