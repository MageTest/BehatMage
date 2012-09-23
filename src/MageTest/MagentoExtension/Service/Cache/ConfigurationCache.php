<?php

namespace MageTest\MagentoExtension\Service\Cache;

class ConfigurationCache
{
    /**
     * Internal instance of MageApp
     *
     * @var Mage_Core_Model_App
     **/
    private $mageApp;
    
    public function __construct($mageApp)
    {
        $this->mageApp = $mageApp;
    }

    // FIXME This is brutal but it is late
    public function clear()
    {
        $this->mageApp->getCacheInstance()->flush();
    }
}
