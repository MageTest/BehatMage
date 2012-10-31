<?php

namespace MageTest\MagentoExtension\Context\Initializer;

use MageTest\MagentoExtension\Service\Bootstrap,
    MageTest\MagentoExtension\Service\CacheManager,
    MageTest\MagentoExtension\Service\ConfigManager,
    MageTest\MagentoExtension\Service\Session,
    MageTest\MagentoExtension\Fixture\FixtureFactory,
    MageTest\MagentoExtension\Context\MagentoAwareInterface;

use Behat\Behat\Context\Initializer\InitializerInterface,
    Behat\Behat\Context\ContextInterface;

class MagentoAwareInitializer implements InitializerInterface
{
    private $app = null;

    private $cacheManager = null;

    private $configManager = null;

    private $factory = null;

    private $session = null;

    public function __construct(Bootstrap $bootstrap, CacheManager $cache,
        ConfigManager $config, FixtureFactory $factory, Session $session)
    {
        $this->app = $bootstrap->app();
        $this->cacheManager = $cache;
        $this->configManager = $config;
        $this->factory = $factory;
        $this->session = $session;
    }

    public function supports(ContextInterface $context)
    {
        return $context instanceof MagentoAwareInterface;
    }

    public function initialize(ContextInterface $context)
    {
        $context->setApp($this->app);
        $context->setConfigManager($this->configManager);
        $context->setCacheManager($this->cacheManager);
        $context->setFixtureFactory($this->factory);
        $context->setSessionService($this->session);
    }
}
