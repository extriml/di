<?php
/**
 * Interface Collection
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
use infiny\di\interfaces\Service as ServiceInterface;
use infiny\di\interfaces\Di as DiInterface;

/**
 * Interface collection of injection
 */
abstract class ParameterIterator implements \Iterator,\Countable
{
    /**
     * Elements collection
     * @var array
     */
    protected $_collection = array();


    /**
     * Initial collection
     * @param null | array $raw
     * @return void
     */
    public function __construct($raw = null)
    {
        if($raw !== null){
            $this->setRaw($raw);   
        }
    }


    /**
     * Iterator - get the current element in the collection
     * @return array
     */
    public function current()
    {
        return current($this->_collection);
    }


    /**
     * Iterator - reset collection
     * @return void
     */
    public function rewind()
    {
        reset($this->_collection);
    }


    /**
     * Iterator - check collection cursor
     * @return bool
     */
    public function valid()
    {
        return $this->current() !== false;
    }


    /**
     * Iterator - get current key collection
     * @return int
     */
    public function key()
    {
        return key($this->_collection);
    }


    /**
     * Iterator - getting next element collection
     * @return bool
     */
    public function next()
    {
        return next($this->_collection);
    }


    /**
     * Countable - getting size collection
     * @return bool
     */
    public function count()
    {
        return sizeof($this->_collection);
    }
    

    /**
     * Setting Raw collection
     * @param array $raw
     * @return void
     */
    public function setRaw($raw)
    {

        $this->_collection = $raw;
    }


    /**
     * Getting raw collection
     * @return array
     */
    public function getRaw()
    {
        return $this->_collection;
    }


    /**
     * Value parameters injection
     * @param  ServiceInterface $service
     * @param  array           $parameters
     * @return array     
     */
    public function buildParameters(ServiceInterface $service, $parameters)
    {
        if (is_array($parameters) === false) {
            throw new Exception("Ivalid type - parameters");     
        }

        if (sizeof($parameters) > 0) {
            foreach ($parameters as $key => $value) {
                
                if (is_string($value) === true) {
                    if (is_null($service->getInjector()) === false) {
                        if ($service->getInjector()->exists($value) === true) {
                            $parameters[$key] = $service->getInjector()->get($value);
                        }
                    }
                }
                
                if ($value instanceof  \Closure) {
                    $parameters[$key] = call_user_func($value,array());
                }

                if (is_array($value) === true) {
                    if(class_exists($value[0]) === true){ 
                        $reflection = new \ReflectionClass($value[0]);
                        $parameters[$key] = $reflection->newInstanceArgs($value[1]);
                    }
                }

            }
        }

        return $parameters;
    }


    /**
     * Interface attributes injection of object
     * @param  ServiceInterface $service
     * @param  object           $object
     * @return object
     */
    abstract public function build(ServiceInterface $service,$object);
}