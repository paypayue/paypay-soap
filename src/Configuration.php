<?php
namespace PayPay;

/**
 *
 * Configuration registry
 *
 */
class Configuration
{
    private $environment  = null;
    private $endpointUrl  = null;
    private $privateKey   = null;
    private $clientId     = null;
    private $platformCode = null;
    private $langCode     = "PT";

    /**
     *
     * @static
     * @var array valid environments, used for validation
     */
    private static $validEnvironments = [
        'development',
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

    public static function fromArray($attribs=array())
    {
        return new self($attribs);
    }

    private function assertHasAccessParams()
    {
        if (empty($this->environment)) {
            throw new Exceptions\Configuration('Configuration::environment needs to be set.');
        }
        if (!$this->isValidEnvironment()) {
            throw new Exceptions\Configuration($this->environment.' is not a valid environment.');
        }
        if (empty($this->endpointUrl())) {
            throw new Exceptions\Configuration('Configuration::endpointUrl needs to be set.');
        }
        if (empty($this->clientId)) {
            throw new Exceptions\Configuration('Configuration::clientId needs to be set.');
        }
        if (empty($this->privateKey)) {
            throw new Exceptions\Configuration('Configuration::privateKey needs to be set.');
        }
        if (empty($this->platformCode)) {
            throw new Exceptions\Configuration('Configuration::platformCode needs to be set.');
        }
    }

    /**
     * Do not use this method directly. Pass in the environment to the constructor.
     */
    private function isValidEnvironment()
    {
        return in_array($this->environment, self::$validEnvironments);
    }

    private function endpointUrl($type = '')
    {
        switch ($this->environment) {
            case 'production':
                $this->endpointUrl = 'https://www.paypay.pt/paypay/paypayservices/paypayservices_c';
                break;
            case 'testing':
                $this->endpointUrl = 'https://paypay.acin.pt/paypaybeta/paypayservices/paypayservices_c';
            case 'development':
            default:
                break;
        }
        if ($type) {
            return $this->endpointUrl . DIRECTORY_SEPARATOR . $type;
        }

        return $this->endpointUrl;
    }

    public function generateAccessToken($date)
    {
        $dataAtual = $date->format("d-m-Y H:i:s");

        return hash('sha256', $this->privateKey . $dataAtual);
    }

    public function asArray()
    {
        return array(
            'wsdl'         => $this->endpointUrl('wsdl'),
            'server'       => $this->endpointUrl('server'),
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
