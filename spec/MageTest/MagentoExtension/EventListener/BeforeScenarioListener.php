<?php

namespace spec\MageTest\MagentoExtension\EventListener;

use PHPSpec2\Specification;

class BeforeScenarioListener implements Specification
{
    function described_with($cacheManager)
    {
        $cacheManager->isAMockOf("MageTest\MagentoExtension\Service\CacheManager");
        $this->beforeScenarioListener->isAnInstanceOf(
            'MageTest\MagentoExtension\EventListener\BeforeScenarioListener',
            array($cacheManager));
    }
    
    function it_should_clear_cache($cacheManager)
    {
        $cacheManager->clear()->shouldBeCalled();
        $this->beforeScenarioListener->beforeScenario();
    }
}