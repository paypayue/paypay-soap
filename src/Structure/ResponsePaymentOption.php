<?php
namespace PayPay\Structure;

class ResponsePaymentOption
{
    /** @var string */
    public $code;
    /** @var string */
    public $name;
    /** @var string */
    public $iconUrl;
    /** @var string */
    public $description;

    public function __construct($params)
    {
        $fields = array('code', 'name', 'iconUrl', 'description');
        foreach ($fields as $fkey => $fvalue) {
            $this->$fvalue = empty($params[$fvalue]) ? '': $params[$fvalue];
        }
    }

}
