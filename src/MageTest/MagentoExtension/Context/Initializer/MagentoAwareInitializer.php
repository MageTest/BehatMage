<?php

namespace MageTest\MagentoExtension\Context\Initializer;

use MageTest\MagentoExtension\Service\Bootstrap,
    MageTest\MagentoExtension\Service\CacheManager,
    MageTest\MagentoExtension\Service\ConfigManager,
    MageTest\MagentoExtension\Service\Session,
    MageTest\MagentoExtension\Fixture\FixtureFactory,
    MageTest\MagentoExtension\Context\MagentoAwareInterface;

use Behat\Behat\Context\Initializer\InitializerInterface,
    Behat\Behat\Context\ContextInterface,
    Behat\Mink\Mink;

class MagentoAwareInitializer implements InitializerInterface
{
    private $app;
    private $cacheManager;
    private $configManager;
    private $factory;
    private $mink;
    private $session;

    public function __construct(Bootstrap $bootstrap, CacheManager $cache,
        ConfigManager $config, FixtureFactory $factory, Mink $mink,
        Session $session)
    {
        $this->app = $bootstrap->app();
        $this->cacheManager = $cache;
        $this->configManager = $config;
        $this->factory = $factory;
        $this->session = $session;
        $this->mink = $mink;

        $this->mink->registerSession(
            '_default',
            new \Behat\Mink\Session(
                new \Behat\Mink\Driver\GoutteDriver(
                    //FIXME: take host from configuration
                    new \Behat\Mink\Driver\Goutte\Client(array('HTTP_HOST'=>'magentoee.development.local'))
                )
            )
        );
        $this->mink->setDefaultSessionName('_default');
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
        $context->setMink($this->mink);
    }
}
