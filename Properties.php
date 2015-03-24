<?php
/**
 * Properties Collection
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
 * A collection of properties for injection
 */
class Properties extends ParameterIterator
{
    /**
     * Magic method - setting property collection
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key,$value)
    {
        $this->_collection[$key] = $value;
    }


    /**
     * Magic method - getting property collection
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->_collection[$key];
    }

    /**
     * Injection properties in object
     * @param  ServiceInterface $service
     * @param  object           $object
     * @return object
     */
    public function build(ServiceInterface $service, $object)
    {
        $parameters = $this->buildParameters($service,$this->_collection);
        if (sizeof($parameters) > 0) {
            foreach ($parameters as $key => $value) {
                if (property_exists($object, $key) === false) {
                    throw new Exception("Property {$key} not found.");
                }

                $object->$key = $value;
            }
        } 
        return $object;
    }
}