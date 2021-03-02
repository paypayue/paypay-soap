<?php
namespace PayPay;

/**
 *
 * Webhook request handler
 *
 */
class WebhookHandler
{
    private $hookAction = null;
    private $hookDate   = null;
    private $hookHash   = null;
    private $payments   = array();

    public function __construct(Configuration $config, $input = array())
    {
        if (empty($input)) {
            throw new Exception\Webhook('Webhook input is empty.', 400);
        }

        foreach ($input as $kind => $value) {
            if (property_exists($this, $kind)) {
                $this->$kind = $value;
            }
        }

        $this->assertHasParams($config);
    }

    public static function fromPost(Configuration $config)
    {
        return new self($config, $_POST);
    }

    public static function fromJSON(Configuration $config)
    {
        $input = json_decode(file_get_contents('php://input'),true);
        return new self($config, $input);
    }

    private function assertHasParams($config)
    {
        if (empty($this->hookAction)) {
            throw new Exception\Webhook('Webhook::hookAction needs to be set.', 400);
        }
        if (empty($this->hookDate)) {
            throw new Exception\Webhook('Webhook::hookDate needs to be set.', 400);
        }
        if (empty($this->hookHash)) {
            throw new Exception\Webhook('Webhook::hookHash needs to be set.', 400);
        } else if (!$this->hasValidSignature($config->getPrivateKey())) {
            throw new Exception\Webhook('Webhook::signature is not valid.', 403);
        }
    }

    /**
     * Loop the payments with a custom callback
     * @param  callable $callback A function to be called on each payment
     */
    public function eachPayment($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException("Argument must be callable");
        }
        foreach ($this->payments as $payment) {
            call_user_func_array($callback, array($payment));
        }
    }

    /**
     * Validates the request hash using the client private key
     * @param  string  $privateKey
     * @return boolean
     */
    private function hasValidSignature($privateKey)
    {
        if (!isset($_SERVER['HTTP_PAYPAY_SIGNATURE'])) {
            return false;
        }

        $body = file_get_contents("php://input");

        $signature = str_replace(',', '&', $_SERVER['HTTP_PAYPAY_SIGNATURE']);

        parse_str($signature, $output);

        return (int)($output['ts']) > (time() - 1800) &&
            hash_equals($output['hash'], hash_hmac('sha256', $body.$output['ts'], $privateKey));
    }
}
