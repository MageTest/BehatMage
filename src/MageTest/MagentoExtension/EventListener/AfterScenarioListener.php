<?php

namespace MageTest\MagentoExtension\EventListener;

use MageTest\MagentoExtension\Fixture\FixtureFactory;

class AfterScenarioListener
{
    private $factory;

    public function __construct(FixtureFactory $factory)
    {
        $this->factory = $factory;
    }

    public function afterScenario()
    {
        $this->factory->clean();
    }
}
