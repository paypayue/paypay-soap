<?php
namespace PayPay\Structure;

/**
* Class RequestShippingAddress
*
* This class define the shipping address information associated with payment
* 
*/
class RequestShippingAddress
{
    /** @var String ISO 3166-1*/
    public $country;

    /** @var String */
    public $state;

    /** @var String */
    public $stateName;
 
    /** @var String */
    public $city;
 
    /** @var String */
    public $street1;

    /** @var String */
    public $street2;
 
    /** @var String */
    public $postCode;
 
    public function __construct($shippingAddress)
    {
        $this->country   = $shippingAddress['country'  ];
        $this->state     = $shippingAddress['state'    ];
        $this->stateName = $shippingAddress['stateName'];
        $this->city      = $shippingAddress['city'     ];
        $this->street1   = $shippingAddress['street1'  ];
        $this->street2   = $shippingAddress['street2'  ];
        $this->postCode  = $shippingAddress['postCode' ];
    }
}
