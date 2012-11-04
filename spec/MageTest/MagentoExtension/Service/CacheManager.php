<?php

namespace spec\MageTest\MagentoExtension\Service;

use PHPSpec2\ObjectBehavior;

class CacheManager extends ObjectBehavior
{
    /**
     * @param MageTest\MagentoExtension\Service\Bootstrap $bootstrap
     */
    function let($bootstrap)
    {
        $this->beConstructedWith($bootstrap);
    }

    /**
     * @param MageTest\MagentoExtension\Service\Cache\ConfigurationCache $cache
     */
    function it_should_clear_configuration_by_default($cache)
    {
        $this->addSection('configuration', $cache);

        $cache->clear()->shouldBeCalled();

        $this->clear();
    }
}
