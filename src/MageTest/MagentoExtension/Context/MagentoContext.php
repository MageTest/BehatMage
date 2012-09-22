<?php

namespace MageTest\MagentoExtension\Context;

use Mage_Core_Model_App as MageApp;
use Magetest\MagentoExtension\Context\MagentoAwareContext,
    Magetest\MagentoExtension\Service\ConfigManager,
    Magetest\MagentoExtension\Service\CacheManager,
    Magetest\MagentoExtension\Fixture\FixtureFactory;

use Behat\MinkExtension\Context\MinkContext,
    Behat\Behat\Context\ContextInterface;

class MagentoContext implements MagentoAwareInterface, ContextInterface
{
    private $app;
    private $configManager;
    private $cacheManager;
    private $factory;

    public function setApp(MageApp $app)
    {
        $this->$app = $app;
    }

    public function setConfigManager(ConfigManager $config)
    {
        $this->configManager = $config;
    }

    public function setCacheManager(CacheManager $cache)
    {
        $this->cacheManager = $cache;
    }

    public function setFixtureFactory(FixtureFactory $factory)
    {
        $this->factory = $factory;
    }

    public function getFixture($identifier)
    {
        return $this->factory->create($identifier);
    }
}