<?php
namespace PayPay;

final class PayPayWebservice extends SoapClient {

    private $requestParams;
    private $response;
    private $configParams;

    public function __construct(\PayPay\Configuration $config)
    {
        $configParams             = $config->asArray();
        $PAYPAY_WEBSERVICE_WSDL   = $configParams['wsdl'        ];
        $PAYPAY_WEBSERVICE_SERVER = $configParams['server'      ];
        $PAYPAY_WEBSERVICE_CODE   = $configParams['platformCode'];
        $PAYPAY_WEBSERVICE_KEY    = $configParams['privateKey'  ];
        $PAYPAY_WEBSERVICE_NIF    = $configParams['clientId'    ];
        $PAYPAY_WEBSERVICE_LANG   = $configParams['langCode'    ];

        $classmap = array(
            'RequestEntity'                     => 'RequestEntity',
            'ResponseIntegrationState'          => 'ResponseIntegrationState',
            'RequestInterval'                   => 'RequestInterval',
            'RequestPaymentReference'           => 'RequestPaymentReference',
            'ResponseEntityPayments'            => 'ResponseEntityPayments',
            'ResponseEntityPaymentReferences'   => 'ResponseEntityPaymentReferences',
            'ResponseEntityPaymentsDetails'     => 'ResponseEntityPaymentsDetails',
            'RequestCreditCardPayment'          => 'RequestCreditCardPayment',
            'ResponseCreditCardPayment'         => 'ResponseCreditCardPayment',
            'RequestReferenceDetails'           => 'RequestReferenceDetails',
            'ResponseGetPayment'                => 'ResponseGetPayment'
        );

        $options = array (
            'classmap'           => $classmap,
            'location'           => $PAYPAY_WEBSERVICE_SERVER,
            'connection_timeout' => $config->getTimeout()
        );

        $date = new \DateTime();
        $date->modify("+10 minutes");
        $dataAtual = $date->format("d-m-Y H:i:s");

        $this->entity = new \PayPay\Structures\RequestEntity(
            $PAYPAY_WEBSERVICE_CODE,
            $config->generateAccessToken($date),
            $dataAtual,
            $PAYPAY_WEBSERVICE_NIF,
            $PAYPAY_WEBSERVICE_LANG
        );

        libxml_disable_entity_loader(false);
        parent::__construct($PAYPAY_WEBSERVICE_WSDL, $options);

    }

    /**
     * Creates a new payment reference via PayPay.
     *
     * @param  array               $paymentData Payment information ex: amount
     * @return ResponseGetPayment  $result      Webservice response
     */
    public function createPaymentReference($paymentData)
    {
        $requestPayment = new \PayPay\Structures\RequestReferenceDetails($paymentData);
        $this->response = parent::createPaymentReference($this->entity, $requestPayment);

        /**
         * Se integração com o paypay tiver algum problema de configuração no PayPay.
         */
        if ($this->response->integrationState->state == 0) {
            throw new \PayPay\Exception\IntegrationState($this->response->integrationState);
        }

        /**
         * A integração está a funcionar mas o pagamento pedido ultrapassa algum limite estabelecido.
         * (ex: montante máximo diário, por referência, etc.)
         */
        if ($this->response->state == 0) {
            throw new \PayPay\Exception\CreatePaymentReference($this->response);
        }

        return $this->response;
    }


    /**
     * Calls PayPay Webservice to request a credit card payment through.
     *
     * @param  array $order_info            The order details containing the amount and user data.
     * @return ResponseCreditCardPayment    The webservice response containing the relevant payment data.
     */
    public function doWebPayment($order_info)
    {

        $order            = new \PayPay\Structures\RequestPaymentOrder($order_info);
        $buyerInfo        = new \PayPay\Structures\RequestBuyerInfo($order_info);
        $returnUrlSuccess = $order_info['RETURN_URL'];
        $returnUrlCancel  = $order_info['CANCEL_URL'];
        $method           = $order_info['method'    ];
        $payment          = new RequestCreditCardPayment($order, $returnUrlSuccess, $returnUrlCancel, $method, $buyerInfo);

        $this->response = parent::doWebPayment($this->entity, $payment);

        /**
         * Se a resposta retornar algum erro previsto.
         */
        if ($this->response->requestState->state == 0) {
            throw new \PayPay\Exception\DoWebPayment($this->response);
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
        $requestPayment = new \PayPay\Structures\RequestPaymentDetails($token, $transactionId);

        $this->response = parent::checkWebPayment($this->entity, $requestPayment);

        if ($this->response->requestState->state == 0) {
            throw new \PayPay\Exception\CheckWebPayment($this->response);
        }

        return $this->response;
    }

    /**
     * Calls the PayPay Webservice to check the state of payments in a given interval.
     * @param  string $startDate
     * @param  string $endDate
     * @return ResponseEntityPayments
     */
    public function getEntityPayments($startDate, $endDate)
    {
        $requestInterval = new \PayPay\Structures\RequestInterval($startDate, $endDate);

        $this->response = parent::getEntityPayments($this->entity, $requestInterval);

        if ($this->response->integrationState->state == 0) {
            throw new \PayPay\Exception\IntegrationState($this->response->integrationState);
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
        $requestReferenceDetails = new \PayPay\Structures\RequestEntityPayments();
        if ($payments) {
            foreach ($payments as $key => $value) {
                $requestPayment = new \PayPay\Structures\RequestReferenceDetails(
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
            throw new \PayPay\Exception\CheckEntityPayments($this->response);
        }

        return $this->response;
    }
}
