<?php

/*
 * This file is part of the Behat Magento Extension
 *
 * (c) James Cowie <jcowie@sessiondigital.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MageTest\MagentoExtension;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use MageTest\MagentoExtension\Driver\MagentoFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\MinkExtension\ServiceContainer\Driver\DriverFactory;
use Behat\MinkExtension\ServiceContainer\Driver\GoutteFactory;
use Behat\MinkExtension\ServiceContainer\Driver\SahiFactory;
use Behat\MinkExtension\ServiceContainer\Driver\SaucelabsFactory;
use Behat\MinkExtension\ServiceContainer\Driver\Selenium2Factory;
use Behat\MinkExtension\ServiceContainer\Driver\SeleniumFactory;
use Behat\MinkExtension\ServiceContainer\Driver\ZombieFactory;
use Behat\Testwork\EventDispatcher\ServiceContainer\EventDispatcherExtension;
use Behat\Testwork\ServiceContainer\Exception\ProcessingException;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Magento extension for Behat class.
 *
 */
class Extension implements ExtensionInterface
{
    const KERNEL_ID = 'behat.magento.driver.app';

    /**
     * Returns the extension config key.
     *
     * @return string
     */
    public function getConfigKey()
    {
        return 'magento';
    }

    /**
     * Initializes other extensions.de
     *
     * This method is called immediately after all extensions are activated but
     * before any extension `configure()` method is called. This allows extensions
     * to hook into the configuration of other extensions providing such an
     * extension point.
     *
     * @param ExtensionManager $extensionManager
     */
    public function initialize(ExtensionManager $extensionManager)
    {
        if (null !== $minkExtension = $extensionManager->getExtension('mink')) {
            $minkExtension->registerDriverFactory(new MagentoFactory());
        }
    }

    /**
     * Setups configuration for the extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {

    }

    /**
     * Loads extension services into temporary container.
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/services'));
        $loader->load('core.xml');

        $container->setDefinition(
            self::KERNEL_ID,
            new Definition('MageTest\MagentoExtension\Driver\MageApp', array($container))
        );
    }

    /**
     * Processes shared container after all extensions loaded.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {

    }
}
