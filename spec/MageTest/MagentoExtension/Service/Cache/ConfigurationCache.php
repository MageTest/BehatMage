<?php

namespace spec\MageTest\MagentoExtension\Service\Cache;

use PHPSpec2\Specification;

$mage = new \Mage_Core_Model_App;

class ConfigurationCache implements Specification
{
    function it_should_exist()
    {
        $this->object->shouldNotBe(null);
    }
    
    /**
     * @param Prophet $mage mock of \Mage_Core_Model_App
     */
    function it_should_clear_the_configuration_cache($mage)
    {
        $mage->cleanCache(array('configuration'))->shouldBeCalled();
        $this->configurationCache->clear();
    }
}