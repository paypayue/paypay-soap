<?php
namespace PayPay\Structure;

class ResponseGetPayment {

    /** @var ResponseIntegrationState */
    public $integrationState;

    /** @var Int */
    public $state;

    /** @var String */
    public $err_code;

    /** @var String */
    public $err_msg;

    /** @var Int */
    public $idPayment;

    /** @var Int */
    public $amount;

    /** @var Int */
    public $creditCardPayment;

    /** @var Int */
    public $atmPayment;

    /** @var String */
    public $atmEntity;

    /** @var Int */
    public $mbwPayment;

    /** @var String */
    public $reference;

    /** @var String */
    public $hash;

    /** @var String */
    public $linkPayment;

    /** @var String */
    public $productCode;

    /** @var String */
    public $productDesc;

    /** @var String */
    public $validStartDate;

    /** @var String */
    public $validEndDate;

    /** @var ResponsePaymentOption[] */
    public $paymentOptions;
}
