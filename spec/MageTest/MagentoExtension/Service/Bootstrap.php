<?php

namespace spec\MageTest\MagentoExtension\Service;

use PHPSpec2\ObjectBehavior;

class Bootstrap extends ObjectBehavior
{
    function it_bootstraps_a_mage_app()
    {
        $this->app()->shouldBeAnInstanceOf('Mage_Core_Model_App');
    }

    function it_provides_a_reflection_of_Mage()
    {
        $this->getMageReflection()->shouldBeAnInstanceOf('ReflectionClass');
    }

    function it_provides_a_reflection_of_Mage_Core_Model_App()
    {
        $this->getMageCoreModelAppReflection()->shouldBeAnInstanceOf('ReflectionClass');
    }
}
