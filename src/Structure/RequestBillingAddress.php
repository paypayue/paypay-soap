<?php
namespace PayPay\Structure;

/**
* Class RequestBillingAddress 
*
* This class define the billing address information associated with payment
* 
*/
class RequestBillingAddress
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

	public function __construct($billingAddress)
	{
		$this->country   = $billingAddress['country'  ];
		$this->state     = $billingAddress['state'    ];
		$this->stateName = $billingAddress['stateName'];
		$this->city      = $billingAddress['city'     ];
		$this->street1   = $billingAddress['street1'  ];
		$this->street2   = $billingAddress['street2'  ];
		$this->postCode  = $billingAddress['postCode' ];
	}
}