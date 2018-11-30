<?php

namespace PayPay\Structure;

class RequestPaymentOption
{
    /** @var String */
    public $code;

    /** @var String */
    public $type;

    public function __construct($code, $type = 'DEFAULT')
    {
        $this->code = $code;
        $this->type = $type;
    }

    /**
     * Convenience factory method for a RequestPaymentOption object
     * @example RequestPaymentOption::MULTIBANCO(PaymentMethodType::REALTIME);
     *
     * @param string $method
     * @param array $arguments
     * @return RequestPaymentOption
     */
    public static function __callStatic($method, $arguments)
    {
        if (count($arguments) === 0) {
            return new RequestPaymentOption(PaymentMethodCode::code($method));
        }

        return new RequestPaymentOption(PaymentMethodCode::code($method), $arguments[0]);
    }
}
