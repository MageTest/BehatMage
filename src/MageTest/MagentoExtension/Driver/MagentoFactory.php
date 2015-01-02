<?php

namespace MageTest\MagentoExtension\Driver;

use Behat\MinkExtension\ServiceContainer\Driver\DriverFactory;
use MageTest\MagentoExtension\Extension;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class MagentoFactory implements DriverFactory
{

    /**
     * Gets the name of the driver being configured.
     *
     * This will be the key of the configuration for the driver.
     *
     * @return string
     */
    public function getDriverName()
    {
        return 'magento';
    }

    /**
     * Defines whether a session using this driver is eligible as default javascript session
     *
     * @return boolean
     */
    public function supportsJavascript()
    {
        return false;
    }

    /**
     * Setups configuration for the driver factory.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
    }

    /**
     * Builds the service definition for the driver.
     *
     * @param array $config
     *
     * @return Definition
     */
    public function buildDriver(array $config)
    {
        if (!class_exists('Behat\Mink\Driver\BrowserKitDriver')) {
            throw new \RuntimeException(
                'Install MinkBrowserKitDriver in order to use the magento driver.'
            );
        }

        return new Definition('MageTest\MagentoExtension\Driver\MageAppDriver', array(
            new Reference(Extension::KERNEL_ID),
            '%mink.base_url%',
        ));
    }
}
