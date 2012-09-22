<?php

namespace MageTest\MagentoExtension\Service\Cache;

class ConfigurationCache
{
    /**
     * Internal instance of MageApp
     *
     * @var Mage_Core_Model_App
     **/
    var $mageApp;
    
    public function __construct($mageApp)
    {
        $this->mageApp = $mageApp;
    }

    public function clear()
    {
        $this->mageApp->cleanCache(array('configuration'));
    }
}
