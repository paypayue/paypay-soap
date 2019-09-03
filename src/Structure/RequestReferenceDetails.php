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

    /** @var String */
    public $validStartDate;

    /** @var String */
    public $validEndDate;

    /** @var RequestPaymentOption[] */
    public $paymentOptions;

    /** @var String */
    public $bankAccountCode;

    public function __construct($data)
    {
        $this->amount         = !empty($data['amount']) ? $data['amount']:'';
        $this->productCode    = !empty($data['productCode']) ? $data['productCode']:'';
        $this->productDesc    = !empty($data['productDesc']) ? $data['productDesc']:'';
        $this->reference      = !empty($data['reference']) ? $data['reference']:'';
        $this->paymentId      = !empty($data['paymentId']) ? $data['paymentId']:'';
        $this->validStartDate = !empty($data['validStartDate']) ? $data['validStartDate']:null;
        $this->validEndDate   = !empty($data['validEndDate']) ? $data['validEndDate']:null;
        $this->paymentOptions = !empty($data['paymentOptions']) ? $data['paymentOptions']:null;
    }

    /**
     * Set the payment options for the payment reference.
     *
     * @param RequestPaymentOption[] $paymentOptions
     * @return RequestReferenceDetails
     */
    public function withPaymentOptions($paymentOptions)
    {
        $this->paymentOptions = $paymentOptions;
        return $this;
    }

    /**
     * Sets the bank account code for the payment reference.
     *
     * @param string $bankAccountCode
     * @return RequestReferenceDetails
     */
    public function withBankAccount($bankAccountCode)
    {
        $this->bankAccountCode = $bankAccountCode;
        return $this;
    }
}
