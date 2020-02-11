<?php
namespace PayPay\Structure;

class RequestCancelPayment {

    /** @var string */
    public $idTransaction;

    /** @var string */
    public $hash;

    /** @var string */
    public $remarks;

    /** @var boolean */
    public $ignoreUnsupported;

    public function __construct($idTransaction = null, $paymentHash = null, $remarks = null, $ignoreUnsupported = false)
    {
        $this->idTransaction = $idTransaction;
        $this->hash = $paymentHash;
        $this->remarks = $remarks;
        $this->ignoreUnsupported = $ignoreUnsupported;
    }

    /**
     * Set the value of ignoreUnsupported
     *
     * @return  self
     */
    public function ignoreUnsupported()
    {
        $this->ignoreUnsupported = true;

        return $this;
    }
}
