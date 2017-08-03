<?php
namespace PayPay;

class WebserviceEventManager
{
    const ON_BEFORE_SEND     = 'paypay.beforesend';
    const ON_ERROR           = 'paypay.error';
    const ON_SUCCESS         = 'paypay.success';

    private $webserviceObject;
    private $events;
    private $eventResults;

    public function __construct($params)
    {

        $this->webserviceObject = $params[0];
        $this->events           = array();
        $this->eventResults     = array();
    }

    public function __call($functionName, $arguments)
    {
        if (method_exists($this, $functionName)) {
            return call_user_func_array( array( $this, $functionName ), $arguments );
        }

        $request = $this->getArgumentsNames($functionName, $arguments);
        $response = array();

        $methodInfo = array(get_class($this->webserviceObject), $functionName);

        try {
            $this->trigger(self::ON_BEFORE_SEND, array_merge($methodInfo, array($request, $response)));

            $response = call_user_func_array(array(($this->webserviceObject), $functionName), $arguments);

            $this->trigger(self::ON_SUCCESS, array_merge($methodInfo, array($request, $response)));

        } catch (Exception $ex) {
            $this->trigger(self::ON_ERROR, array_merge($methodInfo, array($request, $this->webserviceObject->getResponse(), $ex)));
        }


        return $response;
    }

    private function getArgumentsNames($functionName, $arguments)
    {
        $reflector      = new \ReflectionClass($this->webserviceObject);
        $parameters     = $reflector->getMethod($functionName)->getParameters();
        $argumentsNames = array();

        foreach ($parameters as $key => $value) {
            $argumentsNames[$value->name] = $arguments[$key];
        }

        return $argumentsNames;
    }


    public function trigger($event, $args=array())
    {
        if (isset($this->events[$event])) {
            foreach($this->events[$event] as $func) {
                $this->eventResults[$event][] = call_user_func_array($func, $args);
            }
        }
    }

    public function bind($event, Closure $func)
    {

        $this->events[$event][] = $func;
        $this->eventResults[$event] = array();
    }

    public function lastEventResult($event)
    {
        return end($this->eventResults[$event]);
    }
}
