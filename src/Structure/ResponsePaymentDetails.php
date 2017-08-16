<?php
namespace PayPay\Structure;

class ResponsePaymentDetails
{
    /** @var ResponseIntegrationState */
    public $requestState;

    /** @var Int */
    public $paid;

    /** @var String */
    public $paymentDate;

    public function __construct($params)
    {
        $this->requestState = $params['requestState'];
        $this->paid         = empty($params['paid']) ? '': $params['paid'];
        $this->paymentDate  = empty($params['paymentDate']) ? '': $params['paymentDate'];
    }
}
