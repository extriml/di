<?php

namespace infiny\di\tests;

use infiny\di\Service;
use infiny\di\Properties;
use infiny\di\Di;

class PropertiesTest extends \PHPUnit_Framework_TestCase
{

    function testCollection()
    {
        $properties = new Properties;
        $test = "value";
        $properties->test = $test;

        $this->assertEquals($properties->test,$test);
    }

    function testBuild()
    {
        $object = new \stdClass;
        $object->test1  = 1;
        $object->test2 = 2;
        $object->test3 = 3;
        $service = new Service($object,false);
        
        $test1 = "value1";
        $test2 = "value2";
        $test3 = "value3";
        $service->properties()->test1 = $test1;
        $service->properties()->test2 = $test2;
        $service->properties()->test3 = $test3;

        
        $resultObject = $service->properties()->build($service,$object); 
        
        $this->assertEquals($resultObject->test1,$test1);
        $this->assertEquals($resultObject->test2,$test2);
        $this->assertEquals($resultObject->test3,$test3);

    }
}