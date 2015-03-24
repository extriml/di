<?php
/**
 * Arguments Collection
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
 * A collection of arguments of constructor for injection
 */
class Arguments extends ParameterIterator
{

    /**
     * Magic method - setting arguments collection
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->_collection[$key] = $value;
    }


    /**
     * Magic method - getting arguments collection
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->_collection[$key];
    }   


    /**
     * Injection constructor object
     * @param  ServiceInterface $service
     * @param  object           $object
     * @return object
     */
    public function build(ServiceInterface $service,$object)
    {
        $reflection = new \ReflectionClass($service->getDefinition());
        return $reflection->newInstanceArgs(array_values($this->buildParameters($service,$this->_collection)));
    } 
}