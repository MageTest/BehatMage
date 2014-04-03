<?php
/**
 * BehatMage
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License, that is bundled with this
 * package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 *
 * http://opensource.org/licenses/MIT
 *
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email
 * to <magetest@sessiondigital.com> so we can send you a copy immediately.
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Context\Initializer
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace MageTest\MagentoExtension\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use MageTest\MagentoExtension\Service\Bootstrap,
    MageTest\MagentoExtension\Service\CacheManager,
    MageTest\MagentoExtension\Service\ConfigManager,
    MageTest\MagentoExtension\Service\Session,
    MageTest\MagentoExtension\Fixture\FixtureFactory,
    MageTest\MagentoExtension\Context\MagentoAwareInterface;

use Behat\Behat\Context\Initializer\InitializerInterface,
    Behat\Behat\Context\ContextInterface;

/**
 * MagentoAwareInitializer
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Context\Initializer
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class MagentoAwareInitializer implements ContextInitializer
{
    private $app = null;

    private $cacheManager = null;

    private $configManager = null;

    private $factory = null;

    private $session = null;

    /**
     * @param Bootstrap         $bootstrap
     * @param CacheManager      $cache
     * @param ConfigManager     $config
     * @param FixtureFactory    $factory
     * @param Session           $session
     */
    public function __construct(Bootstrap $bootstrap, CacheManager $cache,
                                ConfigManager $config, FixtureFactory $factory, Session $session)
    {
        $this->app = $bootstrap->app();
        $this->cacheManager = $cache;
        $this->configManager = $config;
        $this->factory = $factory;
        $this->session = $session;
    }
    /**
     * Initializes provided context.
     *
     * @param Context $context
     */
    public function initializeContext(Context $context)
    {
        if (!$context instanceof MagentoAwareInterface) {
            return;
        }

        $context->setApp($this->app);
        $context->setConfigManager($this->configManager);
        $context->setCacheManager($this->cacheManager);
        $context->setFixtureFactory($this->factory);
        $context->setSessionService($this->session);
    }

}
