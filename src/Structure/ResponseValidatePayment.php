<?php
namespace PayPay\Structure;

class ResponseValidatePayment {

    /** @var ResponseIntegrationState */
    public $integrationState;

    /** @var ResponsePaymentOption[] */
    public $paymentOptions;


    public function __construct($params)
    {
        $fields = array(
                        'integrationState',
                        'paymentOptions');
        foreach ($fields as $fkey => $fvalue) {
            $this->$fvalue = empty($params[$fvalue]) ? '': $params[$fvalue];
        }
    }

}

?>
