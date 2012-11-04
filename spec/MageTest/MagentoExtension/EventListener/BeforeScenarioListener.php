<?php

namespace spec\MageTest\MagentoExtension\EventListener;

use PHPSpec2\ObjectBehavior;

class BeforeScenarioListener extends ObjectBehavior
{
    /**
     * @param MageTest\MagentoExtension\Service\CacheManager $cacheManager
     */
    function let($cacheManager)
    {
        $this->beConstructedWith($cacheManager);
    }

    function it_should_clear_cache($cacheManager)
    {
        $cacheManager->clear()->shouldBeCalled();

        $this->beforeScenario();
    }
}
