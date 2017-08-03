<?php
namespace PayPay;

/**
 *
 * Configuration registry
 *
 */
class Configuration
{
    private $environment = null;
    private $privateKey = null;
    private $clientId = null;
    private $platformCode = null;
    private $langCode = "PT";
    private $timeout = 30;

    /**
     *
     * @static
     * @var array valid environments, used for validation
     */
    private static $validEnvironments = [
        'testing',
        'production',
    ];

    public function __construct($attribs = [])
    {
        foreach ($attribs as $kind => $value) {
            $this->$kind = $value;
        }

        $this->assertHasAccessParams();
    }

    private function assertHasAccessParams()
    {
        if (empty($this->environment)) {
            throw new Exception\Configuration('PayPay\\Configuration::environment needs to be set.');
        } else if ($this->isValidEnvironment($this->environment)) {
            throw new Exception\Configuration('PayPay\\Configuration::environment is not a valid environment.');
        } else if (empty($this->clientId)) {
            throw new Exception\Configuration('PayPay\\Configuration::clientId needs to be set.');
        } else if (empty($this->privateKey)) {
            throw new Exception\Configuration('PayPay\\Configuration::privateKey needs to be set.');
        } else if (empty($this->platformCode)) {
            throw new Exception\Configuration('PayPay\\Configuration::platformCode needs to be set.');
        }
    }

    /**
     * Do not use this method directly. Pass in the environment to the constructor.
     */
    private function isValidEnvironment($value)
    {
        return in_array($value, self::$validEnvironments);
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    private function setTimeout($value)
    {
        $this->timeout = $value;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }

    public function wsdlUrl()
    {
        return sprintf('%s/%s', $this->endpointUrl(), 'wsdl');
    }

    public function serverUrl()
    {
        return sprintf('%s/%s', $this->endpointUrl(), 'server');
    }

    public function langCode()
    {
        return $this->langCode;
    }

    private function endpointUrl()
    {
        switch ($this->environment) {
            case 'production':
                $serverName = 'https://www.paypay.pt/paypay/paypayservices/paypayservices_c';
                break;
            case 'testing':
            default:
                $serverName = 'https://paypay.acin.pt/paypaybeta/paypayservices/paypayservices_c';
                break;
        }

        return $serverName;
    }

    private function generateAccessToken($date)
    {
        $dataAtual = $date->format("d-m-Y H:i:s");

        return hash('sha256', $this->getPrivateKey() . $dataAtual);
    }

    public function asArray()
    {
        return array(
            'wsdl'         => $this->wsdlUrl(),
            'server'       => $this->serverUrl(),
            'privateKey'   => $this->privateKey,
            'clientId'     => $this->clientId,
            'platformCode' => $this->platformCode,
            'langCode'     => $this->langCode,
        );
    }

    /**
     * log message to default logger
     *
     * @param string $message
     *
     */
    public function logMessage($message)
    {
        error_log('[PayPay] ' . $message);
    }
}
