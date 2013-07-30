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

use Behat\Gherkin\Node\TableNode;
use SensioLabs\Behat\PageObjectExtension\Context\PageFactory;
use SensioLabs\Behat\PageObjectExtension\Context\PageObjectAwareInterface;

/**
 * MagentoContext
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Context
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class MagentoContext extends RawMagentoContext implements PageObjectAwareInterface
{
    private $pageFactory = null;

    /**
     * @When /^I go to the "(?P<page>[^"]*)" admin page$/
     */
    public function iOpenAdminUri($page)
    {
        $urlModel = new \Mage_Adminhtml_Model_Url();
        $page = $this->getPage($page);
        $secretKey = $urlModel->getSecretKey($page->getMainController(), $page->getModuleName());
        $page->open(array('secretKey' => $secretKey));
    }

    /**
     * @When /^I log in as admin user "([^"]*)" identified by "([^"]*)"$/
     */
    public function iLoginAsAdmin($username, $password)
    {
        $sid = $this->getSessionService()->adminLogin($username, $password);
        $this->getSession()->setCookie('adminhtml', $sid);
    }

    /**
     * @When /^I set config value for "([^"]*)" to "([^"]*)" in "([^"]*)" scope$/
     */
    public function iSetConfigValueForScope($path, $value, $scope)
    {
        $this->getConfigManager()->setCoreConfig($path, $value, $scope);
    }

    /**
     * @Given /^the cache is clean$/
     * @When /^I clear the cache$/
     */
    public function theCacheIsClean()
    {
        $this->getCacheManager()->clear();
    }

    /**
     * @Given /the following products exist:/
     */
    public function theProductsExist(TableNode $table)
    {
        $hash = $table->getHash();
        $fixtureGenerator = $this->getFixture('product');
        foreach ($hash as $row) {
            $row['stock_data'] = array();
            if (isset($row['is_in_stock'])) {
                $row['stock_data']['is_in_stock'] = $row['is_in_stock'];
                unset($row['is_in_stock']);
            }
            if (isset($row['qty'])) {
                $row['stock_data']['qty'] = $row['qty'];
                unset($row['qty']);
            }

            $fixtureGenerator->create($row);
        }
    }

    public function getPage($name)
    {
        return $this->pageFactory->createPage($name);
    }

    public function setPageFactory(PageFactory $factory)
    {
        $this->pageFactory = $factory;
    }
}
