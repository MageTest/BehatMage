<?php

namespace MageTest\MagentoExtension\Service;

use MageTest\MagentoExtension\Servcie\Cache\ConfigurationCache;

use Mage_Core_Model_App as MageApp;

class CacheManager
{
    public function __construct(Bootstrap $bootstrap)
    {
        $this->bootstrap = $bootstrap;
    }

    /**
     * Array of cache sections / tags to be modified
     *
     * @var array
     **/
    private $sections = array();

    public function addSection($name, $service)
    {
        $this->sections[$name] = $service;
    }

    public function clear()
    {
        if (empty($this->sections)) {
            $this->addDefaultSections();
        }
        foreach ($this->sections as $name => $service) {
            $service->clear();
        }
    }

    private function addDefaultSections()
    {
        $app = $this->bootstrap->createMage();
        $this->sections['config'] = new Cache\ConfigurationCache($app);
    }
}
