<?php
namespace PayPay\Structure;

class RequestCreditCardPayment {

    const METHOD_CREDIT_CARD = 'CC';
    const METHOD_MULTIBANCO  = 'MB';
    const METHOD_MB_WAY      = 'MW';

    /** @var RequestPaymentOrder */
    public $order;

    /** @var String */
    public $returnUrlSuccess;

    /** @var String */
    public $returnUrlCancel;

    /**
     * Forma de pagamento:
     * CC - Cartão de crédito;
     * MB - Multibanco;
     * MW - MB WAY;
     * @var String
     */
    public $method;

    /** @var RequestBuyerInfo */
    public $buyer;

    public function __construct(
        $order,
        $returnUrlSuccess,
        $returnUrlCancel  = "",
        $method           = 'CC',
        $buyer            = null
    ) {
        $this->order            = $order;
        $this->returnUrlSuccess = $returnUrlSuccess;
        $this->returnUrlCancel  = $returnUrlCancel;
        $this->buyer            = $buyer;
        $this->method           = $method;
    }

}
