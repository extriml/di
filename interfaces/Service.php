<?php
/**
 * Service
 * @package interfaces
 * @subpackage  infiny\di
 * @author Alex Orlov <mail@alexxorlovv.name>
 * @version 1.0.0
 * @since 2014-12-21
 * @license   MIT
 * @copyright  2014 INFINY
 */

namespace infiny\di\interfaces;

use infiny\di\interfaces\Di as DiInterface;


/**
 * Interface service
 */
interface Service
{
    /**
     * Interface initial service
     * @param string | array | object | closure $definition -  Definition raw init
     * @param bool $shared - Shared init
     * @return null
     */
    public function __construct($definition, $shared);


    /**
     * Interface generates the resulting object service
     * @param  DiInterface $injector Object - called Dependency Ijection
     * @return object - resulting object service
     */
    public function resolve(DiInterface $injector);


    /**
     * Interface get Collection Properies
     * @return Properties - Collection properties
     */
    public function properties();


    /**
     * Interface get Collection Arguments
     * @return Arguments - Collection arguments
     */


    /**
     * Interface get Collection Arguments
     * @return Arguments - Collection arguments
     */
    public function arguments();


    /**
     * Interface get Collection Calls
     * @return Calls - Collection calls
     */
    public function calls();


    /**
     * Interface setting injector
     * @param DiInterface $injector
     * @return void
     */
    public function setInjector(DiInterface $injector);


    /**
     * Interface getting injector
     * @return DiInterface injector
     */
    public function getInjector();


    /**
     * Interface check shared type
     * @return bool $shared
     */
    public function isShared();


    /**
     * Interface getting shared type
     * @return bool $shared
     */
    public function getShared();


    /**
     * Interface setting shared type
     * @param bool $shared
     * @return void
     */
    public function setShared($shared);


    /**
     * Interface setting shared service
     * @return null | object 
     */
    public function getSharedService();


    /**
     * Interface setting shared service
     * @param object $service
     * @return void
     */
    public function setSharedService($service);


    /**
     * Interface getting way init service
     * @return object | closure | array | string
     */
    public function getDefinition();


    /**
     * Interface setting way init service
     * @param object | closure | array | string $definition
     * @return void
     */
    public function setDefinition($definition);    
}