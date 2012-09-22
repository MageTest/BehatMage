<?php

namespace MageTest\MagentoExtension\EventListener;

use MageTest\MagentoExtension\Service\CacheManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BeforeScenarioListener implements EventSubscriberInterface 
{
    private $cacheManager;

    public function __construct(CacheManager $manager)
    {
        $this->cacheManager = $manager;
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            'beforeScenario' => 'beforeScenario'
        );
    }

    public function beforeScenario()
    {
        $this->cacheManager->clear();
    }
}