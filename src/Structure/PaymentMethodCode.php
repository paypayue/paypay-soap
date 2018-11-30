<?php

namespace PayPay\Structure;

class PaymentMethodCode {
    const CREDIT_CARD = "CC";
    const MULTIBANCO  = "MB";
    const MBWAY       = "MW";

    /**
     * Helper method to get a code using the class constant name.
     *
     * @param string $constant The Payment Method Code constant
     * @return string The defined constant
     */
    public static function code($constant)
    {
        $ref = new \ReflectionClass('\PayPay\Structure\PaymentMethodCode');
        $constants = $ref->getConstants();
        if (!isset($constants[$constant])) {
            throw new \InvalidArgumentException("Invalid payment method code constant " . $constant);
        }

        return $ref->getConstant($constant);
    }

}
