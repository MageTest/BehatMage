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
 * @package    features
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
use Behat\Behat\Exception\PendingException;
use MageTest\MagentoExtension\Context\MagentoContext;

/**
 * Features context.
 *
 * @category   MageTest
 * @package    features
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class FeatureContext extends MagentoContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
    }

    /**
     * @When /^I run any scenario$/
     */
    public function iRunAnyScenario()
    {

    }

    /**
     * @Given /^we have some files in the config cache of magento$/
     */
    public function weHaveSomeFilesInTheConfigCacheOfMagento()
    {
        // TODO Can't create as part of the background as it happens after
        // the before hook
        // $dir = Mage::getBaseDir('cache');
        // exec("mkdir -p $dir/mage--a && touch $dir/mage--a/mage--PDO123");
        // sleep(5);
    }

    /**
     * @Then /^the before hook will call the cache manager and clear the cache$/
     */
    public function theBeforeHookWillCallTheCacheManagerAndClearTheCache()
    {
        throw new PendingException();
        $dir = Mage::getBaseDir('cache');
        if (count(glob("$dir/mage*")))
        {
            throw new RuntimeException("File should not exist");
        }
    }

    /**
     * @Then /^the after hook will clean up and configuration and fixtures$/
     */
    public function theAfterHookWillCleanUpAndConfigurationAndFixtures()
    {
        throw new PendingException();
    }
}
