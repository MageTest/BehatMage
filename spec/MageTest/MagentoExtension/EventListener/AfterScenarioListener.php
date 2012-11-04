<?php

namespace spec\MageTest\MagentoExtension\EventListener;

use PHPSpec2\ObjectBehavior;

class AfterScenarioListener extends ObjectBehavior
{
    /**
     * @param MageTest\MagentoExtension\Fixture\FixtureFactory $factory
     */
    function let($factory)
    {
        $this->beConstructedWith($factory);
    }

    function it_should_clean_away_any_generated_fixtures($factory)
    {
        $factory->clean()->shouldBeCalled();

        $this->afterScenario();
    }
}
