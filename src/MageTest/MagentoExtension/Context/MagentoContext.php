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
 * @subpackage Context
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace MageTest\MagentoExtension\Context;

use Mage_Core_Model_App as MageApp;
use MageTest\MagentoExtension\Context\MagentoAwareContext,
    MageTest\MagentoExtension\Service\ConfigManager,
    MageTest\MagentoExtension\Service\CacheManager,
    MageTest\MagentoExtension\Service,
    MageTest\MagentoExtension\Fixture\FixtureFactory,
    MageTest\MagentoExtension\Service\Session;

use Behat\MinkExtension\Context\RawMinkContext,
    Behat\Gherkin\Node\TableNode;

/**
 * MagentoContext
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Context
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class MagentoContext extends RawMinkContext implements MagentoAwareInterface
{
    /**
     * @var MageApp
     */
    private $app;

    /**
     * @var ConfigManager
     */
    private $configManager;

    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var FixtureFactory
     */
    private $factory;

    /**
     * @var Session
     */
    private $sessionService;

    /**
     * @When /^I open admin URI "([^"]*)"$/
     */
    public function iOpenAdminUri($uri)
    {
        $urlModel = new \Mage_Adminhtml_Model_Url();
        $m = explode('/', ltrim($uri, '/'));
        // Check if frontName matches a configured admin route
        if ($this->app->getFrontController()->getRouter('admin')->getRouteByFrontName($m[0])) {
            $processedUri = "/admin/{$m[1]}/{$m[2]}/key/".$urlModel->getSecretKey($m[0], $m[1])."/{$m[2]}";
            $this->getSession()->visit($processedUri);
        } else {
            throw new \InvalidArgumentException('$uri parameter should start with a valid admin route and contain controller and action elements');
        }
    }

    /**
     * @When /^I log in as admin user "([^"]*)" identified by "([^"]*)"$/
     */
    public function iLoginAsAdmin($username, $password)
    {
        $sid = $this->sessionService->adminLogin($username, $password);
        $this->getSession()->setCookie('adminhtml', $sid);
    }

    /**
     * @When /^I am on "([^"]*)"$/
     */
    public function iAmOn($uri)
    {
        $this->getSession()->visit($uri);
    }

    /**
     * @When /^I set config value for "([^"]*)" to "([^"]*)" in "([^"]*)" scope$/
     */
    public function iSetConfigValueForScope($path, $value, $scope)
    {
        $this->configManager->setCoreConfig($path, $value, $scope);
    }

    /**
     * @Given /^the cache is clean$/
     * @When /^I clear the cache$/
     */
    public function theCacheIsClean()
    {
        $this->cacheManager->clear();
    }

    /**
     * @Given /the following products exist:/
     */
    public function theProductsExist(TableNode $table)
    {
        $hash = $table->getHash();
        $fixtureGenerator = $this->factory->create('product');
        foreach ($hash as $row) {
            $row['stock_data'] = array();
            if (isset($row['is_in_stock'])) {
                $row['stock_data']['is_in_stock'] = $row['is_in_stock'];
            }
            if (isset($row['is_in_stock'])) {
                $row['stock_data']['qty'] = $row['qty'];
            }

            $fixtureGenerator->create($row);
        }
    }

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

    public function setCacheManager(CacheManager $cache)
    {
        $this->cacheManager = $cache;
    }

    public function setFixtureFactory(FixtureFactory $factory)
    {
        $this->factory = $factory;
    }

    public function setSessionService(Session $session)
    {
        $this->sessionService = $session;
    }

    public function getFixture($identifier)
    {
        return $this->factory->create($identifier);
    }
}
