<?php

namespace spec\MageTest\MagentoExtension\Service\Cache;

use PHPSpec2\ObjectBehavior;

class ConfigurationCache extends ObjectBehavior
{
    /**
     * @param Mage_Core_Model_App   $mageApp
     * @param Mage_Core_Model_Cache $cacheInstance
     */
    function let($mageApp, $cacheInstance)
    {
        $mageApp->getCacheInstance()->willReturn($cacheInstance);

        $cacheInstance->flush()->shouldBeCalled();

        $this->beConstructedWith($mageApp);
    }

    function it_should_clear_the_configuration_cache($mageApp, $cacheInstance)
    {
        $this->clear();
    }
}
