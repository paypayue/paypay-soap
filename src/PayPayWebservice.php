<?php
namespace PayPay;

final class PayPayWebservice extends \SoapClient {

    private $requestParams;
    private $response;
    private $configParams;

    public function __construct(Configuration $config, Structures\RequestEntity $entity)
    {
        $configParams             = $config->asArray();
        $PAYPAY_WEBSERVICE_WSDL   = $configParams['wsdl'        ];
        $PAYPAY_WEBSERVICE_SERVER = $configParams['server'      ];
        $PAYPAY_WEBSERVICE_CODE   = $configParams['platformCode'];
        $PAYPAY_WEBSERVICE_KEY    = $configParams['privateKey'  ];
        $PAYPAY_WEBSERVICE_NIF    = $configParams['clientId'    ];
        $PAYPAY_WEBSERVICE_LANG   = $configParams['langCode'    ];

        $classmap = array(
            'RequestEntity'                     => '\PayPay\Structure\RequestEntity',
            'ResponseIntegrationState'          => '\PayPay\Structure\ResponseIntegrationState',
            'RequestInterval'                   => '\PayPay\Structure\RequestInterval',
            'RequestPaymentReference'           => '\PayPay\Structure\RequestPaymentReference',
            'ResponseEntityPayments'            => '\PayPay\Structure\ResponseEntityPayments',
            'ResponseEntityPaymentReferences'   => '\PayPay\Structure\ResponseEntityPaymentReferences',
            'ResponseEntityPaymentsDetails'     => '\PayPay\Structure\ResponseEntityPaymentsDetails',
            'RequestCreditCardPayment'          => '\PayPay\Structure\RequestCreditCardPayment',
            'ResponseCreditCardPayment'         => '\PayPay\Structure\ResponseCreditCardPayment',
            'RequestReferenceDetails'           => '\PayPay\Structure\RequestReferenceDetails',
            'ResponseGetPayment'                => '\PayPay\Structure\ResponseGetPayment'
        );

        $options = array (
            'classmap'           => $classmap,
            'location'           => $PAYPAY_WEBSERVICE_SERVER
        );



        $this->entity = $entity;

        libxml_disable_entity_loader(false);
        parent::__construct($PAYPAY_WEBSERVICE_WSDL, $options);

    }

    public static function init(\Configuration $config)
    {
        $configParams = $config->asArray();

        $date = new \DateTime();
        $date->modify("+10 minutes");
        $dataAtual = $date->format("d-m-Y H:i:s");

        $entity = new \Structures\RequestEntity(
            $configParams['platformCode'],
            $config->generateAccessToken($date),
            $dataAtual,
            $configParams['clientId'],
            $configParams['langCode']
        );

        return new self($config, $entity);
    }

    /**
     * Creates a new payment reference via PayPay.
     *
     * @param  array               $paymentData Payment information ex: amount
     * @return ResponseGetPayment  $result      Webservice response
     */
    public function createPaymentReference($paymentData)
    {
        $requestPayment = new \Structures\RequestReferenceDetails($paymentData);
        $this->response = parent::createPaymentReference($this->entity, $requestPayment);

        /**
         * Se integração com o paypay tiver algum problema de configuração no PayPay.
         */
        if ($this->response->integrationState->state == 0) {
            throw new \Exception\IntegrationState($this->response->integrationState);
        }

        /**
         * A integração está a funcionar mas o pagamento pedido ultrapassa algum limite estabelecido.
         * (ex: montante máximo diário, por referência, etc.)
         */
        if ($this->response->state == 0) {
            throw new \Exception\CreatePaymentReference($this->response);
        }

        return $this->response;
    }


    /**
     * Calls PayPay Webservice to request a credit card payment through.
     *
     * @param  array $orderData          The order details containing the amount and user data.
     * @return ResponseCreditCardPayment The webservice response containing the relevant payment data.
     */
    public function doWebPayment($orderData)
    {
        $order            = new \Structures\RequestPaymentOrder($orderData);
        $buyerInfo        = new \Structures\RequestBuyerInfo($orderData);
        $returnUrlSuccess = $orderData['RETURN_URL'];
        $returnUrlCancel  = $orderData['CANCEL_URL'];
        $method           = $orderData['method'    ];
        $payment          = new RequestCreditCardPayment($order, $returnUrlSuccess, $returnUrlCancel, $method, $buyerInfo);

        $this->response = parent::doWebPayment($this->entity, $payment);

        /**
         * Se a resposta retornar algum erro previsto.
         */
        if ($this->response->requestState->state == 0) {
            throw new \Exception\DoWebPayment($this->response);
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
        $requestPayment = new \Structures\RequestPaymentDetails($token, $transactionId);

        $this->response = parent::checkWebPayment($this->entity, $requestPayment);

        if ($this->response->requestState->state == 0) {
            throw new \Exception\CheckWebPayment($this->response);
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
        $requestInterval = new \Structures\RequestInterval($startDate, $endDate);

        $this->response = parent::getEntityPayments($this->entity, $requestInterval);

        if ($this->response->integrationState->state == 0) {
            throw new \Exception\IntegrationState($this->response->integrationState);
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
        $requestReferenceDetails = new \Structures\RequestEntityPayments();
        if ($payments) {
            foreach ($payments as $key => $value) {
                $requestPayment = new \Structures\RequestReferenceDetails(
                    array(
                        'reference' => $value['reference'],
                        'paymentId' => $value['paymentId']
                    )
                );
                $requestReferenceDetails->addPayment($requestPayment);
            }
        }

        $this->response = parent::checkEntityPayments($this->entity, $requestReferenceDetails);

        if ($this->response->requestState->state == 0) {
            throw new \Exception\CheckEntityPayments($this->response);
        }

        return $this->response;
    }
}
