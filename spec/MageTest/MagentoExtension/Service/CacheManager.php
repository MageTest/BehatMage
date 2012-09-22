<?php

namespace spec\MageTest\MagentoExtension\Service;

use PHPSpec2\Specification;

class CacheManager implements Specification
{
    function it_should_exist()
    {
        $this->object->shouldNotBe(null);
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