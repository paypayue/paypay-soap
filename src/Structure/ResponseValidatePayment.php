<?php
namespace PayPay\Structure;

class ResponseValidatePayment
{
    /** @var ResponseIntegrationState */
    public $integrationState;

    /** @var ResponsePaymentOption[] */
    public $paymentOptions;

    /** @var string */
    public $err_msg;

    /** @var string */
    public $err_code;

    public function __construct($params)
    {
        $fields = array(
            'integrationState',
            'paymentOptions',
            'err_code',
            'err_msg'
        );
        foreach ($fields as $fkey => $fvalue) {
            $this->$fvalue = empty($params[$fvalue]) ? '' : $params[$fvalue];
        }
    }
}
