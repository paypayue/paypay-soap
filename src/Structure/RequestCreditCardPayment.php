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

    /** @var String */
    public $returnUrlBack;

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

    /** @var String */
    public $bankAccountCode;

    /** @var RequestBillingAddress */
    public $billingAddress;

    /** @var RequestShippingAddress */
    public $shippingAddress;

    /**
     * Constructor
     *
     * @param RequestPaymentOrder $order
     * @param string $returnUrlSuccess
     * @param string $returnUrlCancel
     * @param string $method
     * @param RequestBuyerInfo $buyer
     * @param string $bankAccountCode
     * @param RequestBillingAddress $billingAddress
     * @param RequestShippingAddress $shippingAddress
     */
    public function __construct(
        $order,
        $returnUrlSuccess,
        $returnUrlCancel  = "",
        $method           = 'CC',
        $buyer            = null,
        $billingAddress   = null,
        $shippingAddress  = null
    ) {
        $this->order            = $order;
        $this->returnUrlSuccess = $returnUrlSuccess;
        $this->returnUrlCancel  = $returnUrlCancel;
        $this->method           = $method;
        $this->buyer            = $buyer;
        $this->billingAddress   = $billingAddress;
        $this->shippingAddress  = $shippingAddress;
    }

    /**
     * Sets the redirect urls on the request
     *
     * @param array $redirects
     * @return RequestCreditCardPayment
     */
    public function withRedirectUrls($redirects)
    {
        if (isset($redirects['success'])) {
            $this->returnUrlSuccess = $redirects['success'];
        }

        if (isset($redirects['cancel'])) {
            $this->returnUrlCancel = $redirects['cancel'];
        }

        if (isset($redirects['back'])) {
            $this->returnUrlBack = $redirects['back'];
        }

        return $this;
    }

    /**
     * Adds the payments methods to the request
     *
     * @param array $methods
     * @return RequestCreditCardPayment
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
     * @return RequestCreditCardPayment
     */
    public function withBuyer(RequestBuyerInfo $buyer)
    {
        $this->buyer = $buyer;
        return $this;
    }

    /**
     * Sets the bank account code for the web payment.
     *
     * @param string $bankAccountCode
     * @return RequestCreditCardPayment
     */
    public function withBankAccount($bankAccountCode)
    {
        $this->bankAccountCode = $bankAccountCode;
        return $this;
    }

    /**
     * Adds the billing address to the request
     *
     * @param RequestBillingAddress $billingAddress
     * @return RequestCreditCardPayment
     */
    public function withBillingAddress(RequestBillingAddress $billingAddress)
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }

    /**
     * Adds the shipping address to the request
     *
     * @param RequestShippingAddress $shippingAddress
     * @return RequestCreditCardPayment
     */
    public function withShippingAddress(RequestShippingAddress $shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }
}
