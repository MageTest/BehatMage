<?php

namespace MageTest\MagentoExtension\Service;

use MageTest\MagentoExtension\Servcie\Cache\ConfigurationCache;

class CacheManager
{
    /**
     * Array of cache sections / tags to be modified
     *
     * @var array
     **/
    var $sections;

    public function addSection($name, $service)
    {
        $this->sections[$name] = $service;
    }

    public function clear()
    {
        foreach ($this->sections as $name => $service) {
            $service->clear();
        }
    }
}
