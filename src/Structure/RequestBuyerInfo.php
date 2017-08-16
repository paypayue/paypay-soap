<?php
namespace PayPay\Structure;

class RequestBuyerInfo
{
    /** @var String */
    public $lastName;

    /** @var String */
    public $firstName;

    /** @var String */
    public $customerId;

    /** @var String */
    public $email;

    public function __construct(
        $buyer
    )
    {
        $this->lastName         = $buyer['lastName'];
        $this->firstName        = $buyer['firstName'];
        $this->customerId       = $buyer['customerId'];
        $this->email            = $buyer['email'];
    }

}
