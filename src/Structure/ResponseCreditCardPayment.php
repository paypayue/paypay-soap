<?php
namespace PayPay\Structure;

class ResponseCreditCardPayment
{
    /** @var ResponseIntegrationState */
    public $requestState;

    /** @var String */
    public $url;

    /** @var String */
    public $token;

    /** @var Int */
    public $idTransaction;

    public function __construct($params)
    {
        $this->requestState     = $params['requestState'];
        $this->url              = empty($params['url']) ? '' : $params['url'];
        $this->token            = empty($params['token']) ? '' : $params['token'];
        $this->idTransaction    = empty($params['idTransaction']) ? '' : $params['idTransaction'];
    }
}
