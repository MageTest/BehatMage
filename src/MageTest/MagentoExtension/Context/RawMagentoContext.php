<?php
/**
 * [application]
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Apache License, Version 2.0 that is
 * bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to <${EMAIL}> so we can send you a copy immediately.
 *
 * @category   [category]
 * @package    [package]
 * @copyright  Copyright (c) 2012 debo <${EMAIL}> (${URL})
 */
namespace MageTest\MagentoExtension\Context;

use Behat\MinkExtension\Context\MinkContext;
use Mage_Core_Model_App as MageApp;
use MageTest\MagentoExtension\Context\MagentoAwareContext;
use MageTest\MagentoExtension\Fixture\FixtureFactory;
use MageTest\MagentoExtension\Service\CacheManager;
use MageTest\MagentoExtension\Service\ConfigManager;
use MageTest\MagentoExtension\Service;
use MageTest\MagentoExtension\Service\Session;

/**
 * [name]
 *
 * @category   [category]
 * @package    [package]
 * @author     debo <${EMAIL}> (${URL})
 */
class RawMagentoContext extends MinkContext implements MagentoAwareInterface
{
    private $app;
    private $configManager;
    private $cacheManager;
    private $factory;
    private $sessionService;

    public function setApp(MageApp $app)
    {
        $this->app = $app;
    }

    public function getApp()
    {
        return $this->app;
    }

    public function setConfigManager(ConfigManager $config)
    {
        $this->configManager = $config;
    }

    public function getConfigManager()
    {
        return $this->configManager;
    }

    public function setCacheManager(CacheManager $cache)
    {
        $this->cacheManager = $cache;
    }

    public function getCacheManager()
    {
        return $this->cacheManager;
    }

    public function setFixtureFactory(FixtureFactory $factory)
    {
        $this->factory = $factory;
    }

    public function getFixtureFactory()
    {
        if (!$this->factory) {
            $this->factory = new FixtureFactory;
        }
        return $this->factory;
    }

    public function getFixture($identifier)
    {
        return $this->getFixtureFactory()->create($identifier);
    }

    public function setSessionService(Session $session)
    {
        $this->sessionService = $session;
    }

    public function getSessionService()
    {
        return $this->sessionService;
    }
}
