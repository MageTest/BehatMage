<?php

namespace MageTest\MagentoExtension\EventListener;

use MageTest\MagentoExtension\Fixture\FixtureFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AfterScenarioListener implements EventSubscriberInterface
{
    private $factory;

    public function __construct(FixtureFactory $factory)
    {
        $this->factory = $factory;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'afterScenario' => 'afterScenario'
        );
    }

    /**
     * clean factory in admin-scope
     */
    public function afterScenario()
    {
        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
        $this->factory->clean();
    }
}
