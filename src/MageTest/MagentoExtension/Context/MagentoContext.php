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
use MageTest\MagentoExtension\Service\ConfigManager;
use MageTest\MagentoExtension\Service\CacheManager;
use MageTest\MagentoExtension\Fixture\FixtureFactory;
use MageTest\MagentoExtension\Service\Session;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Gherkin\Node\TableNode;

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
            $processedUri = "/{$m[0]}/{$m[1]}/{$m[2]}/key/".$urlModel->getSecretKey($m[0], $m[1])."/{$m[2]}";
            $this->getSession()->visit($processedUri);
        } else {
            throw new \InvalidArgumentException('$uri parameter should start with a valid admin route and contain controller and action elements');
        }
    }

    /**
     * @When /^I am logged in as admin user "([^"]*)" identified by "([^"]*)"$/
     * @When /^I log in as admin user "([^"]*)" identified by "([^"]*)"$/
     */
    public function iLoginAsAdmin($username, $password)
    {
        $sid = $this->sessionService->adminLogin($username, $password);
        $this->getSession()->setCookie('adminhtml', $sid);
    }

    /**
     * @Given /^I am logged in as customer "([^"]*)" identified by "([^"]*)"$/
     * @Given /^I log in as customer "([^"]*)" identified by "([^"]*)"$/
     */
    public function iLogInAsCustomerWithPassword($email, $password)
    {
        $sid = $this->sessionService->customerLogin($email, $password);
        $this->getSession()->setCookie('frontend', $sid);
    }

    /**
     * @Given /^(?:|I )am on "(?P<page>[^"]+)"$/
     * @When /^(?:|I )go to "(?P<page>[^"]+)"$/
     */
    public function iAmOn($page)
    {
        $urlModel = new \Mage_Core_Model_Url();
        $m = explode('/', ltrim($page, '/'));
        if ($this->app->getFrontController()->getRouter('standard')->getRouteByFrontName($m[0])) {
            $this->getSession()->visit($this->locatePath($page));
        } else {
            $xml = <<<CONF
<frontend>
    <routers>
        <{module_name}>
            <use>standard</use>
            <args>
                <module>{module_name}</module>
                <frontName>%s</frontName>
            </args>
        <{module_name}>
    </routers>
</frontend>
CONF;
            $alternate = "Or if you are testing a CMS page ensure the URL is correct and the Page is enabled.";
            $config = sprintf((string) $xml, $m[0]);
            throw new \InvalidArgumentException(
                sprintf(
                    "Missing route for the URI '%s', You should the following XML to your config.xml \n %s \n\n%s",
                    $page,
                    $config,
                    $alternate
                )
            );
        }
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
        foreach ($hash as $row) {
            if (isset($row['is_in_stock'])) {
                if (!isset($row['qty'])) {
                    throw new \InvalidArgumentException('You have specified is_in_stock but not qty, please add value for qty.');
                };

                $row['stock_data'] = array(
                    'is_in_stock' => $row['is_in_stock'],
                    'qty' => $row['qty']
                );

                unset($row['is_in_stock']);
                unset($row['qty']);
            }

            $this->factory->create('product', $row);
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

    public function getCacheManager()
    {
        return $this->cacheManager;
    }

    public function setFixtureFactory(FixtureFactory $factory)
    {
        $this->factory = $factory;
    }

    public function setSessionService(Session $session)
    {
        $this->sessionService = $session;
    }

    public function getSessionService()
    {
        return $this->sessionService;
    }

    public function getFixture($identifier)
    {
        return $this->factory->create($identifier);
    }
}
