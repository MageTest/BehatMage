<?php

namespace MageTest\MagentoExtension\Context;

use Mage_Core_Model_App as MageApp;
use MageTest\MagentoExtension\Context\MagentoAwareContext,
    MageTest\MagentoExtension\Service\ConfigManager,
    MageTest\MagentoExtension\Service\CacheManager,
    MageTest\MagentoExtension\Service,
    MageTest\MagentoExtension\Fixture\FixtureFactory;

use Behat\MinkExtension\Context\MinkContext,
    Behat\Behat\Context\ContextInterface;

class MagentoContext extends MinkContext implements MagentoAwareInterface, ContextInterface
{
    private $app;
    private $configManager;
    private $cacheManager;
    private $factory;

    /**
     * @Given /^I log in as admin$/
     */
    public function iLoginAsAdmin()
    {
        //FIXME: move $mink to initializer
        $mink = new \Behat\Mink\Mink(array(
            'goutte1'    => new \Behat\Mink\Session(new \Behat\Mink\Driver\GoutteDriver(new \Behat\Mink\Driver\Goutte\Client(array()))),
        ));
        $mink->setDefaultSessionName('goutte1');
        $this->setMink($mink);
        //FIXME END
        $sessionService = new Service\Session();
        $sid = $sessionService->adminLogin('admin', '123123pass');
        $this->getSession()->setCookie('adminhtml', $sid);
    }

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
     * @Then /^I should see text "([^"]*)"$/
     */
    public function iShouldSeeText($text)
    {
        $select = '//*[text()="'.$text.'"]';
        if (!$this->getSession()->getDriver()->find($select)) {
            throw new \Behat\Mink\Exception\ElementNotFoundException($this->getSession(), 'xpath', $select, null);
        }
    }

    public function setApp(MageApp $app)
    {
        $this->$app = $app;
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

    public function getFixture($identifier)
    {
        return $this->factory->create($identifier);
    }
}
