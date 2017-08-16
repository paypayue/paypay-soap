<?php
namespace PayPay\Structure;

class RequestInterval
{
    /** @var string */
    public $startDate;
    /** @var string */
    public $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }
}
