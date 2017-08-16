<?php
namespace PayPay\Structure;

class RequestReferenceDetails {

    /** @var Int */
    public $amount;

    /** @var String */
    public $reference;

    /** @var Int */
    public $paymentId;

    /** @var String */
    public $productCode;

    /** @var String */
    public $productDesc;

    public function __construct($data)
    {
        $this->amount         = !empty($data['amount']) ? $data['amount']:'';
        $this->productCode    = !empty($data['productCode']) ? $data['productCode']:'';
        $this->productDesc    = !empty($data['productDesc']) ? $data['productDesc']:'';
        $this->reference      = !empty($data['reference']) ? $data['reference']:'';
        $this->paymentId      = !empty($data['paymentId']) ? $data['paymentId']:'';
    }

}
