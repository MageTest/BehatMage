<?php

namespace MageTest\MagentoExtension\Service;

class ConfigManager
{
    protected $bootstrap;
    protected $coreConfig;
    
    public function __construct($bootstrap, $coreConfig)
    {
        $this->bootstrap = $bootstrap;
        $this->coreConfig = $coreConfig;
    }

    public function setCoreConfig($path, $value, $scope = null)
    {
        $this->coreConfig->set($path, $value, $scope);
    }
}
