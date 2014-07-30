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
use MageTest\MagentoExtension\Fixture\BundleProduct;

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

    /**
     * @Given /^the following bundle products exist:$/
     */
    public function theFollowingBundleProductsExist(TableNode $table)
    {
        $hash             = $table->getHash();

        foreach ($hash as $row)
            $this->factory->create('bundle_product', $row);
    }

    /**
     * @Given /^the bundle with sku "([^"]*)" have the following option data:$/
     */
    public function theBundleWithSkuHaveTheFollowingOptionData($sku, TableNode $table)
    {
        $bundleInRegistry = \Mage::registry(BundleProduct::REGISTRY_FIXTURE_PREFIX . $sku);

        if (is_null($bundleInRegistry))
            throw new \InvalidArgumentException("Bundle product with sku $sku could not be found in registry");

        $hash = $table->getHash();

        foreach ($hash as $row) {
            $optionRawData    = array();
            $optionRawData[0] = $row;
            $optionRawData[0]['option_id'] = '';
            $optionRawData[0]['delete'] = '';

            $bundleInRegistry->setCanSaveConfigurableAttributes(false);
            $bundleInRegistry->setCanSaveCustomOptions(true);
            $bundleInRegistry->setBundleOptionsData($optionRawData);
        }
    }

    /**
     * @Given /^the following products are added to the bundle with "([^"]*)" sku:$/
     */
    public function theFollowingProductsAreAddedToTheBundleWithSku($sku, TableNode $table)
    {
        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);

        $bundleInRegistry = \Mage::registry(BundleProduct::REGISTRY_FIXTURE_PREFIX . $sku);

        if (is_null($bundleInRegistry))
            throw new \InvalidArgumentException("Bundle product with sku $sku could not be found in registry");

        $model = \Mage::getModel('catalog/product');
        $hash = $table->getHash();

        $selectionRawData    = array();
        $selectionRawData[0] = array();
        $bundleProductId      = $model->getIdBySku($sku);
        $bundledProductIds   = array();

        if($bundleProductId > 0) {
            $bundledProductIds = $bundleInRegistry->getTypeInstance(true)->getChildrenIds($bundleInRegistry->getId(), false);
        }

        foreach ($hash as $index => $row) {
            $productId = $model->getIdBySku($row['sku']);
            // this is to prevent SQLSTATE[23000]: Integrity constraint violation
            if (BundleProduct::isSelectionProduct($productId, $bundledProductIds))
                continue;

            if ((int) $productId == 0)
                throw new \Exception("Simple product with sku {$row['sku']} could not be found");

            unset($row['sku']);

            $row['product_id'] = $productId;
            $row['selection_id'] = '';
            $row['option_id'] = '';
            $row['delete'] = '';

            $selectionRawData[0][$index] = $row;
        }

        if (count($selectionRawData[0]) > 0) {
            \Mage::register('current_product', $bundleInRegistry, true);
            $bundleInRegistry->setBundleSelectionsData($selectionRawData);
            $bundleInRegistry->setCanSaveBundleSelections(true);
            $bundleInRegistry->setAffectBundleProductSelections(true);
            $bundleInRegistry->save();
            var_dump($bundleInRegistry->getId());
            \Mage::app()->setCurrentStore(\Mage_Core_Model_App::DISTRO_STORE_ID);
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
