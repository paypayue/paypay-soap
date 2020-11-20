<?php
namespace PayPay;

/**
 *
 * Configuration registry
 *
 */
class Configuration
{
    private $environment;
    private $privateKey;
    private $clientId;
    private $platformCode;
    private $platformInfo;
    private $langCode;
    private $cacheWsdl;

    /**
     *
     * @static
     * @var array valid environments, used for validation
     */
    private static $ENVIRONMENTS = array(
        'development',
        'testing',
        'production',
    );

    public function __construct($attribs = array())
    {
        $this->langCode  = "PT";
        $this->cacheWsdl = WSDL_CACHE_BOTH;

        $fields = array(
            'environment',
            'privateKey',
            'clientId',
            'platformCode',
            'langCode',
            'platformInfo'
        );

        foreach ($fields as $fvalue) {
            if (isset($attribs[$fvalue]) && $attribs[$fvalue] != '')  {
                $this->$fvalue = $attribs[$fvalue];
            }
        }

        $this->assertHasAccessParams();
    }

    public static function setup($attribs=array())
    {
        return new self($attribs);
    }

    private function assertHasAccessParams()
    {
        if (empty($this->clientId)) {
            throw new Exception\Configuration('Configuration::clientId needs to be set.');
        }
        if (empty($this->privateKey)) {
            throw new Exception\Configuration('Configuration::privateKey needs to be set.');
        }
        if (empty($this->platformCode)) {
            throw new Exception\Configuration('Configuration::platformCode needs to be set.');
        }
        if (empty($this->environment)) {
            throw new Exception\Configuration('Configuration::environment needs to be set.');
        }
        if (!$this->isValidEnvironment()) {
            throw new Exception\Configuration($this->environment.' is not a valid environment.');
        }
    }


    /**
     * Do not use this method directly. Pass in the environment to the constructor.
     */
    private function isValidEnvironment()
    {
        return in_array($this->environment, self::$ENVIRONMENTS);
    }

    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    public function generateAccessToken($date)
    {
        $dataAtual = $date->format("d-m-Y H:i:s");

        return hash('sha256', $this->privateKey . $dataAtual);
    }

    public function asArray()
    {
        return array(
            'privateKey'   => $this->privateKey,
            'clientId'     => $this->clientId,
            'platformCode' => $this->platformCode,
            'langCode'     => $this->langCode,
            'platformInfo' => $this->platformInfo
        );
    }

    /**
     * Log message to default logger
     *
     * @param string $message
     *
     */
    public function logMessage($message)
    {
        error_log('[PayPay] ' . $message);
    }

    /**
     * Get the value of cacheWsdl
     */
    public function getCacheWsdl()
    {
        return $this->cacheWsdl;
    }

    /**
     * Sets the mode of wsdl cache. Must be one of the following:
     * WSDL_CACHE_BOTH,
     * WSDL_CACHE_DISK,
     * WSDL_CACHE_MEMORY,
     * WSDL_CACHE_NONE
     *
     */
    public function setCacheWsdl($cacheWsdl)
    {
        if (!in_array($cacheWsdl, [WSDL_CACHE_BOTH, WSDL_CACHE_DISK, WSDL_CACHE_MEMORY, WSDL_CACHE_NONE])) {
            throw new \InvalidArgumentException("Invalid WSDL cache method.");
        }
        $this->cacheWsdl = $cacheWsdl;
    }
}
