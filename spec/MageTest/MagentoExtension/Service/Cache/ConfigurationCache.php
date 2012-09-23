<?php

namespace spec\MageTest\MagentoExtension\Service\Cache;

use PHPSpec2\Specification;

class ConfigurationCache implements Specification
{
    function described_with($mageApp, $cacheInstance)
    {
        $cacheInstance->isAMockOf("Mage_Core_Model_Cache");
        $cacheInstance->flush()
            ->shouldBeCalled();

        $mageApp->isAMockOf("Mage_Core_Model_App");
        $mageApp->getCacheInstance()
            ->willReturn($cacheInstance);

        $this->configurationCache->isAnInstanceOf(
            'MageTest\MagentoExtension\Service\Cache\ConfigurationCache',
            array($mageApp));
    }

    function it_should_clear_the_configuration_cache($mageApp, $cacheInstance)
    {
        $this->configurationCache->clear();
    }
}