<?php
namespace PayPay\Structure;

class RequestPaymentOrder
{
    /** @var string */
    public $idTransaction;

    /** @var Int */
    public $amount;

    /** @var String */
    public $reference;

    /** @var String */
    public $hash;

    /** @var string */
    public $productCode;

    /** @var string */
    public $productDesc;

    /** @var String */
    public $validStartDate;

    /** @var String */
    public $validEndDate;    

    public function __construct(
        $order
    )
    {
        $this->reference     = !empty($order['reference']) ? $order['reference'] : '';
        $this->hash          = !empty($order['hash']) ? $order['hash'] : '';
        $this->amount        = !empty($order['amount']) ? $order['amount'] : '';
        $this->idTransaction = !empty($order['idTransaction']) ? $order['idTransaction'] : '';
        $this->productCode   = !empty($order['productCode']) ? $order['productCode'] : '';
        $this->productDesc   = !empty($order['productDesc']) ? $order['productDesc'] : '';
        $this->validStartDate = !empty($order['validStartDate']) ? $order['validStartDate'] : '';
        $this->validEndDate   = !empty($order['validEndDate']) ? $order['validEndDate'] : '';           
    }

}
