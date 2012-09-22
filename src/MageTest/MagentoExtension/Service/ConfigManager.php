<?php

namespace MageTest\MagentoExtension\Service;

use MageTest\MagentoExtension\Service\Config\CoreConfig,
    MageTest\MagentoExtension\Service\Bootstrap;

class ConfigManager
{
    protected $bootstrap;
    protected $coreConfig;

    public function __construct(Bootstrap $bootstrap, CoreConfig $coreConfig)
    {
        $this->bootstrap = $bootstrap;
        $this->coreConfig = $coreConfig;
    }

    public function setCoreConfig($path, $value, $scope = null)
    {
        $this->coreConfig->set($path, $value, $scope);
    }
}
