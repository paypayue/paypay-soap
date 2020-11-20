<?php
namespace PayPay\Structure;

class RequestEntity
{
    /** @var string */
    public $platformCode;
    /** @var string */
    public $hash;
    /** @var string */
    public $date;
    /** @var string */
    public $nif;
    /** @var string */
    public $lang;
    /** @var array */
    public $info;

    public function __construct($platformCode, $hash, $date, $nif, $lang = null, $info = array())
    {
        $this->platformCode = $platformCode;
        $this->hash         = $hash;
        $this->date         = $date;
        $this->nif          = $nif;
        $this->lang         = $lang;
        $this->info         = $info;
    }
}
