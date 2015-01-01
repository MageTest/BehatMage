<?php
use Behat\Mink\Mink;
use MageTest\MagentoExtension\Service\CacheManager;
use MageTest\MagentoExtension\Service\ConfigManager;
use MageTest\MagentoExtension\Service\Session;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

trait MagentoPageTrait
{
    /**
     * @var Mage_Core_Model_App
     */
    private $_app;

    /**
     * @var Session
     */
    private $_sessionService;

    /**
     * @var CacheManager
     */
    private $_cacheManager;

    /**
     * @var ConfigManager
     */
    private $_configManager;

    /**
     * @var Page|null
     */
    private $_page;

    /**
     * @var Mink
     */
    private $_mink;

    /**
     * @var []
     */
    private $_minkParameters;

    private $_prefix;

    public function setApp(Mage_Core_Model_App $app)
    {
        $this->_app = $app;
    }

    public function setConfigManager(ConfigManager $config)
    {
        $this->_configManager = $config;
    }

    public function setCacheManager(CacheManager $cache)
    {
        $this->_cacheManager = $cache;
    }

    public function setSessionService(Session $session)
    {
        $this->_sessionService = $session;
    }

    public function setMink(Mink $mink)
    {
        $this->_mink = $mink;
    }

    public function setMinkParameters(array $parameters)
    {
        $this->_minkParameters = $parameters;
    }

    private function getCurrentPage()
    {
        return $this->_page;
    }

    private function setCurrentPage(Page $page)
    {
        $this->_page = $page;
    }

    /**
     * @param string $username
     * @param string $password
     */
    private function loginAdminUser($username, $password)
    {
        $this->resetSession();
        $sessionId = $this->_sessionService->adminLogin($username, $password);
        $this->getSession()->setCookie('adminhtml', $sessionId);
    }

    private function getSession($name = null)
    {
        return $this->_mink->getSession($name);
    }

    private function resetSession()
    {
        $session = $this->getSession();
        $session->restart();
        $session->visit($this->_minkParameters['base_url']);
    }

    /**
     * @BeforeScenario
     */
    public function setPrefix()
    {
        $letters = range('a', 'z');

        $random = '';

        for ($i = 0; $i < 10; $i++) {
            $random .= $letters[array_rand($letters)];
        }

        $this->_prefix = $random;
    }

    public function getPrefix()
    {
        return $this->_prefix;
    }
}
