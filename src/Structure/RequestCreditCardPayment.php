<?php
namespace PayPay\Structure;

class RequestCreditCardPayment {

    /** @var RequestPaymentOrder */
    public $order;

    /** @var String */
    public $returnUrlSuccess;

    /** @var String */
    public $returnUrlCancel;

    /**
     * Forma de pagamento:
     * CC - Cartão de crédito;
     * MW - MB WAY;
     * @var String
     */
    public $method;

    /** @var RequestBuyerInfo */
    public $buyer;

    public function __construct(
        $order,
        $returnUrlSuccess = "",
        $returnUrlCancel = "",
        $buyer = "",
        $method = null
    ) {
        $this->order            = $order;
        $this->returnUrlSuccess = $returnUrlSuccess;
        $this->returnUrlCancel  = $returnUrlCancel;
        $this->buyer            = $buyer;
        $this->method           = $method;
    }

}

?>
