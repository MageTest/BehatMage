<?php

namespace spec\MageTest\MagentoExtension\Service;

use PHPSpec2\ObjectBehavior;

class ConfigManager extends ObjectBehavior
{
    /**
     * @param MageTest\MagentoExtension\Service\Bootstrap         $bootstrap
     * @param MageTest\MagentoExtension\Service\Config\CoreConfig $coreConfig
     */
    function let($bootstrap, $coreConfig)
    {
        $this->beConstructedWith($bootstrap, $coreConfig);
    }

    function it_should_set_core_config($coreConfig)
    {
        $coreConfig->set('test/path/to/config', 'value', 1)->shouldBeCalled();

        $this->setCoreConfig('test/path/to/config', 'value', 1);
    }
}
