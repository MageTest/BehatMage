<?php

namespace spec\MageTest\MagentoExtension\Service;

use PHPSpec2\Specification;

class Bootstrap implements Specification
{
    function it_bootstraps_a_mage_app()
    {
        $this->bootstrap->createMage()->shouldBeAnInstanceOf('Mage_Core_Model_App');
    }
}