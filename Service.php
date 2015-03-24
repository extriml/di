<?php
/**
 * Service
 * @package di
 * @subpackage  infiny
 * @author Alex Orlov <mail@alexxorlovv.name>
 * @version 1.0.0
 * @since 2014-12-21
 * @license   MIT
 * @copyright  2014 INFINY
 */

namespace infiny\di;

use infiny\di\Calls;
use infiny\di\Properties;
use infiny\di\Arguments;
use infiny\di\exceptions\Exception;
use infiny\di\interfaces\Service as ServiceInterface;
use infiny\di\interfaces\Di as DiInterface;

/**
 * infiny\di\Service
 * 
 * This class implements the storage facility for dependency injection
 */
class Service implements  ServiceInterface
{
    /**
     * Properies Collection
     * @var null | Properties
     */
    protected $properties = null;


    /**
     * Arguments Collection
     * @var null | Arguments
     */
    protected $arguments = null;


    /**
     * Calls Collection 
     * @var null | Calls
     */
    protected $calls = null;   


    /**
     * Raw initialed service
     * @var string | array | object | closure
     */
    protected $definition;


    /**
     * Get initialed service or new init service
     * @var bool
     */
    protected $shared = false;


    /**
     * Initialed injector
     * @var object | null
     */
    protected $injector = null;


    /**
     * Builded raw object
     * @var object | null
     */
    protected $sharedService = null;


    /**
     * Initial service
     * @param string | array | object | closure $definition -  Definition raw init
     * @param bool $shared - Shared init
     * @return void
     */
    public function __construct($definition, $shared)
    {
        $this->shared = $shared;

        if (is_string($definition) === true) {
            $this->definition = $definition;

            if (class_exists($this->definition) === false) {
                throw new Exception("An attempt to initialize the service with an unknown class - {$this->definition}"); 
            }
        } elseif (is_array($definition) === true) {
            $this->definition = $definition["className"];

            if (class_exists($this->definition) === false) {
                throw new Exception("An attempt to initialize the service with an unknown class - {$this->definition}"); 
            }
            
            if (is_array($definition['properties']) === true) {
                $this->properties = new Properties($definition['properties']);
            }

            if (is_array($definition['arguments'])) {
                $this->arguments = new Arguments($definition['arguments']);
            }

            if (is_array($definition['calls'])) {
                $this->calls = new Calls($definition['calls']);
            }
             
        }else {
            $this->definition = $definition;
        }   
    }


    /**
     * Generates the resulting object service
     * @param  DiInterface $injector Object - called Dependency Ijection
     * @return object - resulting object service
     */
    public function resolve(DiInterface $injector)
    {
        if ($this->isShared() === true and 
            is_null($this->sharedService) === false) {
            return $this->sharedService;
        }

        $this->setInjector($injector);
        if ($this->definition instanceof \Closure) {
            $object = call_user_func($this->definition); 
        }

        if (is_object($this->definition) === true) {
            $object = clone $this->definition;  
        }

        if (is_string($this->definition) === true) {
            if (is_null($this->arguments) === false) {
                $object = $this->arguments->build($this,null);
            }else {
                $object = new $this->definition;
            }
        }

        if (is_array($this->definition) === true) {

            $definition = $this->definition;
            $this->definition = $definition["className"];
            
            if (is_array($definition['properties']) === true) {
                $this->properties = new Properties($definition['properties']);
            }

            if (is_array($definition['arguments'])) {
                $this->arguments = new Arguments($definition['arguments']);
                $object = $this->arguments->build($this,null);
            }else {
                $object = new $this->definition;
            }

            if (is_array($definition['calls'])) {
                $this->calls = new Calls($definition['calls']);
            }

           
        }

        if (is_null($this->properties) === false){
            $object = $this->properties->build($this, $object);
        }

        if (is_null($this->calls) === false) {
            $object = $this->calls->build($this, $object);
        }

        $this->sharedService = $object;

        return $object;
    }


    /**
     * Get Collection Properies
     * @return Properties - Collection properties
     */
    public function properties()
    {
        if (is_null($this->properties) === true) {
            $this->properties = new Properties;
        }

        return $this->properties;
    }


    /**
     * Get Collection Arguments
     * @return Arguments - Collection arguments
     */
    public function arguments()
    {
        if (is_null($this->arguments) === true) {
            $this->arguments = new Arguments;
        }

        return $this->arguments;
    }


    /**
     * Get Collection Calls
     * @return Calls - Collection calls
     */
    public function calls()
    {
        if (is_null($this->calls) === true) {
            $this->calls = new Calls;
        }

        return $this->calls;
    }


    /**
     * Setting injector
     * @param DiInterface $injector
     * @return void
     */
    public function setInjector(DiInterface $injector)
    {
        $this->injector = $injector;
    }


    /**
     * Getting injector
     * @return DiInterface injector
     */
    public function getInjector()
    {
        return $this->injector;
    }


    /**
     * Check shared type
     * @return bool $shared

     */
    public function isShared()
    {
        return $this->shared;
    }


    /**
     * Getting shared type
     * @return bool $shared
     */
    public function getShared()
    {
        return $this->shared;
    }


    /**
     * Setting shared type
     * @param bool $shared
     * @return void
     */
    public function setShared($shared)
    {
        if (is_bool($shared) === false) {
            throw new Exception("Invalid type Shared");
        }

        $this->shared = $shared;
    }


    /**
     * Getting shared service
     * @return null | object 
     */
    public function getSharedService()
    {
        return $this->sharedService;
    }


    /**
     * Setting shared service
     * @param object $service
     */
    public function setSharedService($service)
    {
        if (is_object($service) === false) {
            throw new Exception("Invalid type - sharedService");
        }
        $this->sharedService = $service;
    }


    /**
     * Getting way init service
     * @return object | closure | array | string
     */
    public function getDefinition()
    {
        return $this->definition;
    }


    /**
     * Setting way init service
     * @param object | closure | array | string $definition
     * @return void
     */
    public function setDefinition($definition)
    {
        if (is_object($definition) === false and 
            is_array($definition) === false and 
            is_string($definition) === false and 
            ($definition instanceof \Closure) === false) {
            throw new Exception("Invalid type - definition");
        }

        $this->sharedService = null;
        $this->definition = $definition;
    }
}
