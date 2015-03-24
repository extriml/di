<?php
/**
 * Dependency Injector
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
use infiny\di\Service;
use infiny\di\interfaces\Service as ServiceInterface;
use infiny\di\interfaces\Di as DiInterface;


/**
 * Realization dependency injection
 */
class Di implements  \ArrayAccess, DiInterface
{
    /**
     * Collection services
     * @var array
     */
    protected $services =  array();


    /**
     * Collection initialed object services
     * @var array
     */
    protected $sharedServices = array();


    /**
     * Last getting service is shared
     * @var bool
     */
    protected $freshInstanced = false;


    /**
     * Add service
     * @param string  $name Service name
     * @param string | object | array | closure  $definition
     * @param bool $shared
     * @return Service
     */
    public function set($name, $definition, $shared = false)
    {   
        if (is_string($name) === false) {
            throw new Exception("name parameter invalid type");
        }

        if (is_string($definition) === false and 
            is_object($definition) === false and 
            is_array($definition) === false and 
            ($definition instanceof \Closure === false)) {
            throw new Exception("definition parameter invalid type");
        }

        $name = strtolower($name);
        try {
            $this->services[$name] = new Service($definition, $shared);
            $this->services[$name]->setInjector($this);
        } catch(Exception $e) {
            $this->remove($name);
        }

        return $this->services[$name];
    }


    /**
     * Setting shared service
     * @param string $name
     * @param string | array | object | closure $definition
     * @return Service
     */
    public function setShared($name, $definition)
    {
        return $this->set($name, $definition, true);
    }


    /**
     * Getting shared service
     * @param  string $name
     * @return object
     */
    public function getShared($name)
    {
        if (is_string($name) === false) {
            throw new Exception("invalid type - name");
        }
        if ($this->exists($name) === true) {
            return $this->services[$name]->getSharedService();
        }
    }

    /**
     * Adding a service to the ban dubbing
     * @param string  $name
     * @param string | array | object | closure $definition
     * @param bool $shared
     * @return Service
     */
    public function attempt($name, $definition, $shared = false)
    {
        if($this->exists($name) === false) {
            throw new Exception("Failed attempted, service {$name} - exists");
        }
        return $this->set($name, $definition, $shared);
    }


    /**
     * Getting Object
     * @param  string $name name
     * @return object
     */
    public function get($name)
    {
        $name = strtolower($name);
        if (is_string($name) === false) {
            throw new Exception("name parameter invalid type");
        }

        if ($this->exists($name) === false) {
            throw new Exception("Service {$name} not found.");
        }

        return $this->services[$name]->resolve($this);
    }


    /**
     * Getting Service
     * @param  string $key
     * @return Service
     */
    public function getService($key)
    {
        if (is_string($key) === false) {
            throw new Exception("parameter invalid type");
        }

        if ($this->exists($key) === false) {
           throw new Exception("You get undefined service");  
        }

        return $this->services[$key];
    }


    /**
     * Setting service
     * @param string  $name
     * @param Service $service
     * @return Service
     */
    public function setService($name, Service $service)
    {
        if (is_string($name) === false) {
            throw new Exception("Parameter name - invalid type");
        }

        return $this->services[$name] = $service;
    }


    /**
     * Deletting Service
     * @param  string $key
     * @return void
     */
    public function remove($key)
    {
        if (is_string($key) === false) {
            throw new Exception("parameter invalid type");
        }

        if ($this->exists($key) === false) {
            throw new Exception("You delete undefined service"); 
        }

        unset($this->services[strtolower($key)]);   
    }


    /**
     * Check exists service
     * @param  string $key
     * @return bool
     */
    public function exists($key)
    {
        if (is_string($key) === false) {
            throw new Exception("parameter invalid type");
        }

        return isset($this->services[strtolower($key)]);
    }


    /**
     * Getting services collection
     * @return array
     */
    public function getServices()
    {
        return $this->services;
    }


    /**
     * Magic method - setting or getting service
     * @param  string $name
     * @param  array $arguments
     * @return object | Service
     */
    public function __call($name, $arguments)
    {
        $name = strtolower($name);
        $method = substr($name, 0, 3);
        $serviceName = substr($name, 3);

        if ($method === "get") {
            return $this->get($serviceName);
        }

        if ($method === "set") {
            return $this->set($serviceName, $arguments[0]);
        }

        throw new Exception("Call {$method} undefined method type", 1);
    }


    /**
     * Magic method - setting service
     * @param string $key
     * @param string | array | object | closure $value
     * @return Service
     */
    public function __set($key, $value)
    {
        $shared = false;
        
        $key = strtolower($key);

        if (strpos($key,"shared") !== false) {
            $shared = true;
            $key = str_replace("shared", "", $key);
        }
         
        return $this->set($key, $value, $shared);
    }


    /**
     * Magic method - getting building service
     * @param  string $key
     * @return object
     */
    public function __get($key)
    {
        return $this->get($key);
    }


    /**
     * Magic method - deletting service
     * @param string $key
     * @return void
     */
    public function __unset($key)
    {
        $this->remove($key);
    }


    /**
     * Magic method - check exists service
     * @param  string $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->exists($key);
    }


    /**
     * ArrayAccess method - check exists service
     * @param  string $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->exists($key);
    }


    /**
     * ArrayAccess method - getting builded service
     * @param  string $key
     * @return object
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }


    /**
     * ArrayAccess method - setting service
     * @param  string $key
     * @param  string | array | object | closure $value
     * @return Service
     */
    public function offsetSet($key, $value)
    {
        $shared = false;
        
        $key = strtolower($key);

        if (strpos($key,"shared") !== false) {
            $shared = true;
            $key = str_replace("shared", "", $key);
        }
         
        return $this->set($key, $value, $shared);
    }


    /**
     * ArrayAccess method - deletting service
     * @param  string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->remove($key);
    }
}