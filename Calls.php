<?php
/**
 * Calls Collection
 * @package di
 * @subpackage  infiny
 * @author Alex Orlov <mail@alexxorlovv.name>
 * @version 1.0.0
 * @since 2014-12-21
 * @license   MIT
 * @copyright  2014 INFINY
 */

namespace infiny\di;

use infiny\di\exceptions\Exception;
use infiny\di\ParameterIterator;
use infiny\di\interfaces\Service as ServiceInterface;

/**
 * A collection of calls for injection
 */
class Calls extends ParameterIterator
{
    /**
     * Magic method - Setting methods and arguments of method
     * @param  string $method
     * @param  array $arguments
     * @return void
     */
    function __call($method,$arguments)
    {
        if(sizeof($arguments) != 2){
            throw new Exception("Call arguments size not 2");
        }
        $this->_collection[$method][$arguments[0]] = $arguments[1]; 
    }


    /**
     * Injection calls in object
     * @param  ServiceInterface $service
     * @param  object           $object
     * @return object
     */
    public function build(ServiceInterface $service, $object)
    {
        if (sizeof($this->_collection) > 0) {
            foreach ($this->_collection as $key => $value) {
                if (method_exists($object, $key) === false) {
                    throw new Exception("Method {$key} - not found.");
                }

                call_user_func_array(array($object,$key),array_values($this->buildParameters($service,$value)));
            }
        }

        return $object;
    }
}