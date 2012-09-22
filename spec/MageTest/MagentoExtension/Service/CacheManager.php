<?php

namespace spec\MageTest\MagentoExtension\Service;

use PHPSpec2\Specification;

class CacheManager implements Specification
{
    function described_with($bootstrap)
    {
        $bootstrap->isAMockOf('MageTest\MagentoExtension\Service\Bootstrap');
        $this->cacheManager->isAnInstanceOf('MageTest\MagentoExtension\Service\CacheManager', array($bootstrap));
    }

    /**
     * @param Prophet $cache mock of MageTest\MagentoExtension\Service\Cache\ConfigurationCache
     */
    function it_should_clear_configuration_by_default($cache)
    {
        $this->cacheManager->addSection('configuration', $cache);
        $cache->clear()->shouldBeCalled();
        $this->cacheManager->clear();
    }
}