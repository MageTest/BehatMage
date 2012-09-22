<?php

namespace spec\MageTest\MagentoExtension\Service;

use PHPSpec2\Specification;

class ConfigManager implements Specification
{
    function described_with($bootstrap, $coreConfig)
    {
        $bootstrap->isAMockOf('MageTest\MagentoExtension\Service\Bootstrap');
        $coreConfig->isAmockOf('MageTest\MagentoExtension\Service\Config\CoreConfig');
        $this->configManager->isAnInstanceOf(
            'MageTest\MagentoExtension\Service\ConfigManager',
            array($bootstrap, $coreConfig)
        );
    }
    
    function it_should_set_core_config($coreConfig)
    {
        $coreConfig->set()->shouldBeCalled();
        $this->configManager->setCoreConfig('test/path/to/config', 'value', 1);
    }
}