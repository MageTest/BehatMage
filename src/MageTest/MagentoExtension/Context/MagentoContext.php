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

use Behat\MinkExtension\Context\MinkAwareInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Gherkin\Node\TableNode,
    Behat\Mink\Mink;

/**
 * MagentoContext
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Context
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class MagentoContext extends BehatContext implements MinkAwareInterface, MagentoAwareInterface
{
    private $app;
    private $configManager;
    private $cacheManager;
    private $factory;
    private $mink;
    private $minkProperties;
    private $sessionService;

    /**
     * @Given /^I log in as admin user "([^"]*)" identified by "([^"]*)"$/
     */
    public function iLoginAsAdmin($username, $password)
    {
        $sid = $this->sessionService->adminLogin($username, $password);
        $this->mink->getSession()->setCookie('adminhtml', $sid);
    }

    /**
     * @When /^I open admin URI "([^"]*)"$/
     */
    public function iOpenAdminUri($uri)
    {
        $urlModel = new \Mage_Adminhtml_Model_Url();
        if (preg_match('@^/admin/(.*?)/(.*?)((/.*)?)$@', $uri, $m)) {
            $processedUri = "/admin/{$m[1]}/{$m[2]}/key/".$urlModel->getSecretKey($m[1], $m[2])."/{$m[3]}";
            $this->mink->getSession()->visit($processedUri);
        } else {
            throw new \InvalidArgumentException('$uri parameter should start with /admin/ and contain controller and action elements');
        }
    }

    /**
     * @When /^I am on "([^"]*)"$/
     */
    public function iAmOn($uri)
    {
        $this->mink->getSession()->visit($uri);
    }

    /**
     * @Then /^I should see text "([^"]*)"$/
     */
    public function iShouldSeeText($text)
    {
        $select = '//*[text()="'.$text.'"]';
        if (!$this->mink->getSession()->getDriver()->find($select)) {
            throw new \Behat\Mink\Exception\ElementNotFoundException($this->mink->getSession(), 'xpath', $select, null);
        }
    }

    /**
     * @Then /^I should not see text "([^"]*)"$/
     */
    public function iShouldNotSeeText($text)
    {
        $select = '//*[text()="'.$text.'"]';
        if ($this->mink->getSession()->getDriver()->find($select)) {
            throw new \Exception("the given text \"$text\" is unexpectedly found.");
        }
    }

    /**
     * @Given /^I set config value for "([^"]*)" to "([^"]*)" in "([^"]*)" scope$/
     */
    public function iSetConfigValueForScope($path, $value, $scope)
    {
        $this->configManager->setCoreConfig($path, $value, $scope);
    }


    /**
     * @Given /^the cache is clean$/
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

    public function setMink(Mink $mink)
    {
        $this->mink = $mink;
    }

    public function setMinkParameters(array $parameters)
    {
        $this->minkParameters = $parameters;
    }

    public function getFixture($identifier)
    {
        return $this->factory->create($identifier);
    }
}
