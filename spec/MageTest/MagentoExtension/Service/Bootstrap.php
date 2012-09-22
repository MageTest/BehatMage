<?php

namespace spec\MageTest\MagentoExtension\Service;

use PHPSpec2\Specification;

class Bootstrap implements Specification
{
    function it_bootstraps_a_mage_app()
    {
        $this->bootstrap->app()->shouldBeAnInstanceOf('Mage_Core_Model_App');
    }
    
    function it_provides_a_reflection_of_Mage()
    {
        $this->bootstrap->getMageReflection()->shouldBeAnInstanceOf('ReflectionClass');
    }
    
    function it_provides_a_reflection_of_Mage_Core_Model_App()
    {
        $this->bootstrap->getMageCoreModelAppReflection()->shouldBeAnInstanceOf('ReflectionClass');
    }
}