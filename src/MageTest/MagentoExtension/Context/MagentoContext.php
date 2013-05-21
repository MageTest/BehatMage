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

/**
 * MagentoContext
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Context
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class MagentoContext extends RawMagentoContext
{
    /**
     * @When /^I open admin URI "([^"]*)"$/
     */
    public function iOpenAdminUri($uri)
    {
        $urlModel = new \Mage_Adminhtml_Model_Url();
        if (preg_match('@^/admin/(.*?)/(.*?)((/.*)?)$@', $uri, $m)) {
            $processedUri = "/admin/{$m[1]}/{$m[2]}/key/".$urlModel->getSecretKey($m[1], $m[2])."/{$m[3]}";
            $this->getSession()->visit($processedUri);
        } else {
            throw new \InvalidArgumentException('$uri parameter should start with /admin/ and contain controller and action elements');
        }
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
            }
            if (isset($row['is_in_stock'])) {
                $row['stock_data']['qty'] = $row['qty'];
            }

            $fixtureGenerator->create($row);
        }
    }
}
