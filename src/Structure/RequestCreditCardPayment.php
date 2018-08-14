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

    /**
     * Constructor
     *
     * @param RequestPaymentOrder $order 
     * @param string $returnUrlSuccess
     * @param string $returnUrlCancel
     * @param string $method
     * @param RequestBuyerInfo $buyer
     */
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
        $this->method           = $method;
        $this->buyer            = $buyer;
    }

    /**
     * Adds the payments methods to the request 
     *
     * @param array $methods
     * @return void
     */
    public function withMethods($methods)
    {
        $this->method = implode(',', $methods);
        return $this;
    }

    /**
     * Adds the buyer info to the request
     *
     * @param RequestBuyerInfo $buyer
     * @return void
     */
    public function withBuyer(RequestBuyerInfo $buyer)
    {
        $this->buyer = $buyer;
        return $this;
    }
}
