<?php

namespace spec\MageTest\MagentoExtension\EventListener;

use PHPSpec2\Specification;

class AfterScenarioListener implements Specification
{
    function described_with($factory)
    {
        $factory->isAMockOf("MageTest\MagentoExtension\Fixture\FixtureFactory");
        $this->afterScenarioListener->isAnInstanceOf(
            'MageTest\MagentoExtension\EventListener\AfterScenarioListener',
            array($factory));
    }
    
    function it_should_clean_away_any_generated_fixtures($factory)
    {
        $factory->clean()->shouldBeCalled();
        $this->afterScenarioListener->afterScenario();
    }
}