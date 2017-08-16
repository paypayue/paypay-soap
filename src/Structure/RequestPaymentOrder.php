<?php
namespace PayPay\Structures;

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

    public function __construct(
        $order
    )
    {
        $this->reference    = !empty($order['reference']) ? $order['reference'] : '';
        $this->hash         = !empty($order['hash']) ? $order['hash'] : '';
        $this->amount       = !empty($order['amount']) ? $order['amount'] : '';
        $this->idTransaction = !empty($order['idTransaction']) ? $order['idTransaction'] : '';
    }

}
