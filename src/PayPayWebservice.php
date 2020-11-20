<?php
namespace PayPay;

use PayPay\Structure\RequestCancelPayment;
use PayPay\Structure\RequestReferenceDetails;

/**
 *
 * PayPay Webservice Soap Client implementation
 *
 */
final class PayPayWebservice extends \SoapClient
{
    const REVISION = "1.13.0";

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
            'ResponsePayment'                 => '\PayPay\Structure\ResponsePayment',
            'ResponseEntityPaymentReferences' => '\PayPay\Structure\ResponseEntityPaymentReferences',
            'ResponseEntityPaymentsDetails'   => '\PayPay\Structure\ResponseEntityPaymentsDetails',
            'RequestCreditCardPayment'        => '\PayPay\Structure\RequestCreditCardPayment',
            'ResponseCreditCardPayment'       => '\PayPay\Structure\ResponseCreditCardPayment',
            'RequestReferenceDetails'         => '\PayPay\Structure\RequestReferenceDetails',
            'ResponseGetPayment'              => '\PayPay\Structure\ResponseGetPayment',
            'ResponsePaymentOption'           => '\PayPay\Structure\ResponsePaymentOption',
            'RequestPaymentOption'            => '\PayPay\Structure\RequestPaymentOption',
            'PaymentDetails'                  => '\PayPay\Structure\PaymentDetails',
            'PaymentFee'                      => '\PayPay\Structure\PaymentFee',
            'TransferDetails'                 => '\PayPay\Structure\TransferDetails',
            'PaymentRefund'                   => '\PayPay\Structure\PaymentRefund',
            'RequestCancelPayment'            => '\PayPay\Structure\RequestCancelPayment',
            'ResponseCancelPayment'           => '\PayPay\Structure\ResponseCancelPayment',
            'ResponseCancelPaymentOption'     => '\PayPay\Structure\ResponseCancelPaymentOption',
            'ResponsePaymentReferenceError'   => '\PayPay\Structure\ResponsePaymentReferenceError',
            'RequestBuyerInfo'                => '\PayPay\Structure\RequestBuyerInfo',
            'RequestBillingAddress'           => '\PayPay\Structure\RequestBillingAddress',
            'RequestShippingAddress'          => '\PayPay\Structure\RequestShippingAddress',
    );

    public function __construct(Configuration $config, Structure\RequestEntity $entity)
    {
        $this->config = $config;

        $endpoint = self::endpointUrl('server');
        $host = parse_url($endpoint, PHP_URL_HOST);

        $options = array(
            'classmap' => self::$CLASSMAP,
            'location' => $endpoint,
            'cache_wsdl' => $config->getCacheWsdl(),
            'user_agent' => $this->getUserAgent(),
            'stream_context' => stream_context_create(
                array(
                    'ssl' => array(
                        'cafile' => __DIR__ .'/../cert/cacert.pem',
                        'CN_match' => $host,
                        'ciphers' => 'HIGH:!SSLv2:!SSLv3',
                        'disable_compression' => true,
                        'verify_peer' => true,
                        'verify_depth'  => 5,
                        'verify_peer_name' => true,
                        'allow_self_signed' => false
                    )
                )
            )
        );

        $this->entity = $entity;

        $wsdl = self::endpointUrl('wsdl') . $this->getRevisionHash();

        parent::__construct($wsdl, $options);
    }

    private function getUserAgent()
    {
        return 'PayPay-SOAP/' . $this->getRevision();
    }


    private function getRevision()
    {
        return self::REVISION . '-' . $this->config->getEnvironment();
    }

    private function getRevisionHash()
    {
        return '?rev=' . sha1($this->getRevision());
    }

    private function endpointUrl($type = '', $query_string = '')
    {
        $env = $this->config->getEnvironment();
        if (!isset(self::$ENDPOINTS[$env])) {
            if (!defined('PAYPAY_WEBSERVICE_URL')) {
                throw new \InvalidArgumentException(
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
            $configParams['langCode'],
            $configParams['platformInfo']
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
        if (Exception\IntegrationState::check($this->response->integrationState)) {
            throw new Exception\IntegrationState($this->response->integrationState);
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
     * @param   array  $payments List of payments to check
     * @return  ResponseEntityPaymentsDetails Webservice Response
     */
    public function checkEntityPayments($payments = array())
    {
        $requestReferenceDetails = new Structure\RequestEntityPayments();
        if ($payments) {
            foreach ($payments as $key => $value) {
                $requestPayment = $value instanceof RequestReferenceDetails ? $value : new RequestReferenceDetails($value);
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

    /**
     * Cancels the specified payment/transaction and associated payment methods.
     *
     * @param RequestCancelPayment $requestCancelPayment
     * @return ResponseCancelPayment
     */
    public function cancelPayment(RequestCancelPayment $requestCancelPayment)
    {
        $this->response = parent::cancelPayment($this->entity, $requestCancelPayment);

        /**
         * Checks the state of the platform integration.
         */
        if (Exception\IntegrationState::check($this->response->requestState)) {
            throw new Exception\IntegrationState($this->response->requestState);
        }

        if ($this->response->requestState->state == 0) {
            throw new Exception\IntegrationResponse($this->response->requestState->message, $this->response->requestState->code);
        }

        return $this->response;
    }

    /**
     * Calls the PayPay Webservice to save payments generated locally with a configured reference range
     * @param  array  $payments
     * @return ResponseEntityPaymentReferences      Webservice Response
     */
    public function saveEntityPayments($payments = array())
    {
        $this->response = parent::saveEntityPayments($this->entity, $payments);

        /**
         * Checks the payments is valid.
         */
        if (!empty($this->response->paymentReferenceErrors)) {
            throw new Exception\IntegrationMultiResponseError($this->response->integrationState, $this->response->paymentReferenceErrors);
        }

        /**
         * Checks the state of the platform integration.
         */
        if (Exception\IntegrationState::check($this->response->integrationState)) {
            throw new Exception\IntegrationState($this->response->integrationState);
        }

        if ($this->response->integrationState->state == 0) {
            throw new Exception\IntegrationResponse($this->response->integrationState->message, $this->response->integrationState->code);
        }

        return $this->response;
    }
}
