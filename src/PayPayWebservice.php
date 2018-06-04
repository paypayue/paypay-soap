<?php
namespace PayPay;


/**
 *
 * PayPay Webservice Soap Client implementation
 *
 */
final class PayPayWebservice extends \SoapClient {

    private $response;

    /**
     * @var Configuration
     */
    private $config;


    /**
     * @var array
     */
    private static $ENDPOINTS = array(
        'production' => 'https://www.paypay.pt/paypay/paypayservices/paypayservices_c',
        'testing'    => 'https://paypay.acin.pt/paypaybeta/paypayservices/paypayservices_c'
    );

    /**
     * @var array
     */
    private static $DIR = array(
        'wsdl'   => '/wsdl',
        'server' => '/server'
    );

    /**
     * @var array
     */
    private static $CLASSMAP = array(
            'RequestEntity'                   => '\PayPay\Structure\RequestEntity',
            'ResponseIntegrationState'        => '\PayPay\Structure\ResponseIntegrationState',
            'RequestInterval'                 => '\PayPay\Structure\RequestInterval',
            'RequestPaymentReference'         => '\PayPay\Structure\RequestPaymentReference',
            'ResponseEntityPayments'          => '\PayPay\Structure\ResponseEntityPayments',
            'ResponseEntityPaymentReferences' => '\PayPay\Structure\ResponseEntityPaymentReferences',
            'ResponseEntityPaymentsDetails'   => '\PayPay\Structure\ResponseEntityPaymentsDetails',
            'RequestCreditCardPayment'        => '\PayPay\Structure\RequestCreditCardPayment',
            'ResponseCreditCardPayment'       => '\PayPay\Structure\ResponseCreditCardPayment',
            'RequestReferenceDetails'         => '\PayPay\Structure\RequestReferenceDetails',
            'ResponseGetPayment'              => '\PayPay\Structure\ResponseGetPayment'
    );

    public function __construct(Configuration $config, Structure\RequestEntity $entity)
    {
        $this->config = $config;


        $options = array (
            'classmap'     => self::$CLASSMAP,
            'location'     => self::endpointUrl('server'),
            'cache_wsdl'   => WSDL_CACHE_NONE
        );

        $this->entity = $entity;

        // libxml_disable_entity_loader(false);
        parent::__construct(self::endpointUrl('wsdl'), $options);
    }

    private function endpointUrl($type = '')
    {
        $env = $this->config->getEnvironment();
        if (!isset(self::$ENDPOINTS[$env])) {
            if (!defined('PAYPAY_WEBSERVICE_URL')) {
                throw new InvalidArgumentException(
                    "There is no endpoint for the current environment, use the constant PAYPAY_WEBSERVICE_URL"
                );
            }
            $endpoint = constant('PAYPAY_WEBSERVICE_URL');
        } else {
            $endpoint = self::$ENDPOINTS[$env];
        }

        if ($type) {
            return $endpoint . self::$DIR[$type];
        }

        return $endpoint;
    }

    public static function init(Configuration $config)
    {
        $configParams = $config->asArray();

        $date = new \DateTime();
        $date->modify("+10 minutes");
        $dataAtual = $date->format("d-m-Y H:i:s");

        $entity = new Structure\RequestEntity(
            $configParams['platformCode'],
            $config->generateAccessToken($date),
            $dataAtual,
            $configParams['clientId'],
            $configParams['langCode']
        );

        return new self($config, $entity);
    }

    /**
     * Checks the current entity integration state
     * @return ResponseIntegrationState
     */
    public function checkIntegrationState()
    {
        $this->response = parent::checkIntegrationState($this->entity);

        /**
         * Checks the state of the platform integration.
         */
        if (Exception\IntegrationState::check($this->response)) {
            throw new Exception\IntegrationState($this->response);
        }

        return $this->response;
    }


    /**
     * Subscribe to a webhook to receive callbacks from events ocurred at PayPay.
     *
     * @param  Structure\RequestWebhook $requestWebhook
     * @return Structure\ResponseWebhook
     */
    public function subscribeToWebhook(Structure\RequestWebhook $requestWebhook)
    {
        $this->response = parent::subscribeToWebhook($this->entity, $requestWebhook);

        /**
         * Checks the state of the platform integration.
         */
        if (Exception\IntegrationState::check($this->response->integrationState)) {
            throw new Exception\IntegrationState($this->response->integrationState);
        }

        /**
         * Caso ocorra algum erro específico do método.
         */
        if ($this->response->integrationState->state == 0) {
            throw new Exception\IntegrationResponse(
                $this->response->integrationState->message,
                $this->response->integrationState->code
            );
        }

        return $this->response;
    }

    /**
     * Creates a new payment reference via PayPay.
     *
     * @param  array               $paymentData Payment information ex: amount
     * @return ResponseGetPayment  $result      Webservice response
     */
    public function createPaymentReference(Structure\RequestReferenceDetails $paymentData)
    {
        $this->response = parent::createPaymentReference($this->entity, $paymentData);

        /**
         * Checks the state of the platform integration.
         */
        if (Exception\IntegrationState::check($this->response->integrationState)) {
            throw new Exception\IntegrationState($this->response->integrationState);
        }

        /**
         * In case an error code is returned not related to the integration state.
         * (eg.: maximum daily amount exceeded, maximum reference amount, etc.)
         */
        if ($this->response->state == 0) {
            throw new Exception\IntegrationResponse(
                $this->response->err_msg,
                $this->response->err_code
            );
        }

        return $this->response;
    }

    /**
     * Validates a new payment reference via PayPay.
     *
     * @param  array               $paymentData Payment information ex: amount
     * @return ResponseValidatePayment  $result      Webservice response
     */
    public function validatePaymentReference(Structure\RequestReferenceDetails $paymentData)
    {
        $this->response = parent::validatePaymentReference($this->entity, $paymentData);

        /**
         * Checks the state of the platform integration.
         */
        if (Exception\IntegrationState::check($this->response->integrationState)) {
            throw new Exception\IntegrationState($this->response->integrationState);
        }

        /**
         * In case an error code is returned not related to the integration state.
         * (eg.: maximum daily amount exceeded, maximum reference amount, etc.)
         */
        if (!empty($this->response->err_code)) {
            throw new Exception\IntegrationResponse(
                $this->response->err_msg,
                $this->response->err_code
            );
        }

        return $this->response;
    }

    /**
     * Calls PayPay Webservice to request a payment via PayPay redirect.
     *
     * @param  RequestCreditCardPayment $requestWebPayment
     * @return ResponseCreditCardPayment The webservice response containing the relevant payment data.
     */
    public function doWebPayment($requestWebPayment)
    {
        $this->response = parent::doWebPayment($this->entity, $requestWebPayment);

        /**
         * Checks the state of the platform integration.
         */
        if (Exception\IntegrationState::check($this->response->requestState)) {
            throw new Exception\IntegrationState($this->response->requestState);
        }

        /**
         * Se a resposta retornar algum erro previsto.
         */
        if ($this->response->requestState->state == 0) {
            throw new Exception\IntegrationResponse(
                $this->response->requestState->message,
                $this->response->requestState->code
            );
        }

        return $this->response;
    }

    /**
     * Calls PayPay Webservice to check the payment state.
     *
     * @param  RequestPaymentDetails $paymentDetails Request payment
     * @return ResponsePaymentDetails                Response payment details
     */
    public function checkWebPayment($token, $transactionId)
    {
        $requestPayment = new Structure\RequestPaymentDetails($token, $transactionId);

        $this->response = parent::checkWebPayment($this->entity, $requestPayment);

        /**
         * Checks the state of the platform integration.
         */
        if (Exception\IntegrationState::check($this->response->requestState)) {
            throw new Exception\IntegrationState($this->response->requestState);
        }

        if ($this->response->requestState->state == 0) {
            throw new Exception\IntegrationResponse(
                $this->response->requestState->message,
                $this->response->requestState->code
            );
        }

        return $this->response;
    }

    /**
     * Calls the PayPay Webservice to obtain a list of payments whose state was updated in the given interval.
     * @param  string $startDate
     * @param  string $endDate
     * @return ResponseEntityPayments
     */
    public function getEntityPayments($startDate, $endDate)
    {
        $requestInterval = new Structure\RequestInterval($startDate, $endDate);

        $this->response = parent::getEntityPayments($this->entity, $requestInterval);

        /**
         * Checks the state of the platform integration.
         */
        if (Exception\IntegrationState::check($this->response->requestState)) {
            throw new Exception\IntegrationState($this->response->requestState);
        }

        return $this->response;
    }

    /**
     * Obtains the webservice method response.
     * @return mixed The webservice response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Calls the PayPay Webservice to check the state of multiple payments.
     * @param   array                         $payments [description]
     * @return  ResponseEntityPaymentsDetails             [description]
     */
    public function checkEntityPayments($payments = array())
    {
        $requestReferenceDetails = new Structure\RequestEntityPayments();
        if ($payments) {
            foreach ($payments as $key => $value) {
                $requestPayment = new Structure\RequestReferenceDetails(
                    array(
                        'reference' => $value['reference'],
                        'paymentId' => $value['paymentId']
                    )
                );
                $requestReferenceDetails->addPayment($requestPayment);
            }
        }

        $this->response = parent::checkEntityPayments($this->entity, $requestReferenceDetails);

        /**
         * Checks the state of the platform integration.
         */
        if (Exception\IntegrationState::check($this->response->state)) {
            throw new Exception\IntegrationState($this->response->state);
        }

        if ($this->response->state->state == 0) {
            throw new Exception\IntegrationResponse($this->response->state->message, $this->response->state->code);
        }

        return $this->response;
    }
}
