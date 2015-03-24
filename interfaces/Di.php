<?php
/**
 * Di
 * @package interfaces
 * @subpackage  infiny\di
 * @author Alex Orlov <mail@alexxorlovv.name>
 * @version 1.0.0
 * @since 2014-12-21
 * @license   MIT
 * @copyright  2014 INFINY
 */

namespace infiny\di\interfaces;

use infiny\di\Service;

/**
 * Interface injector
 */
interface Di extends \ArrayAccess
{
    /**
     * Interface add service
     * @param string  $name Service name
     * @param string | object | array | closure  $definition
     * @param bool $shared
     * @return Service
     */
    public function set($name, $definition, $shared = false);

    
    /**
     * Interface setting shared service
     * @param string $name
     * @param string | array | object | closure $definition
     * @return Service
     */
    public function setShared($name, $definition);


    /**
     * Interface getting shared service
     * @param  string $name
     * @return object
     */
    public function getShared($name);


    /**
     * Interface adding a service to the ban dubbing
     * @param string  $name
     * @param string | array | object | closure $definition
     * @param bool $shared
     * @return Service
     */
    public function attempt($name, $definition, $shared = false);


    /**
     * Interface getting Object
     * @param  string $name name
     * @return object
     */
    public function get($name);


    /**
     * Interface getting Service
     * @param  string $key
     * @return Service
     */
    public function getService($key);


    /**
     * Interface setting service
     * @param string  $name
     * @param Service $service
     * @return Service
     */
    public function setService($name, Service $service);


    /**
     * Interface deletting Service
     * @param  string $key
     * @return void
     */
    public function remove($key);


    /**
     * Interface check exists service
     * @param  string $key
     * @return bool
     */
    public function exists($key);


    /**
     * Interface getting services collection
     * @return array
     */
    public function getServices();


    /**
     * Interface magic method - setting or getting service
     * @param  string $name
     * @param  array $arguments
     * @return object | Service
     */
    public function __call($name, $arguments);


    /**
     * Interface magic method - setting service
     * @param string $key
     * @param string | array | object | closure $value
     * @return Service
     */
    public function __set($key, $value);


    /**
     * Interface magic method - getting building service
     * @param  string $key
     * @return object
     */
    public function __get($key);


    /**
     * Interface magic method - deletting service
     * @param string $key
     * @return void
     */
    public function __unset($key);


    /**
     * Interface magic method - check exists service
     * @param  string $key
     * @return boolean
     */
    public function __isset($key);


    /**
     * Interface iterator method - check exists service
     * @param  string $key
     * @return bool
     */
    public function offsetExists($key);


    /**
     * Interface iterator method - getting builded service
     * @param  string $key
     * @return object
     */
    public function offsetGet($key);


    /**
     * Interface iterator method - setting service
     * @param  string $key
     * @param  string | array | object | closure $value
     * @return Service
     */
    public function offsetSet($key, $value);


    /**
     * Interface iterator method - deletting service
     * @param  string $key
     * @return void
     */
    public function offsetUnset($key);
}