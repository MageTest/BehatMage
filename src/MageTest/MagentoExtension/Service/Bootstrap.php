<?php

namespace MageTest\MagentoExtension\Service;

class Bootstrap
{

    public function app()
    {
        return \Mage::app();
    }

    public function getMageReflection()
    {
        return new \ReflectionClass('Mage');
    }

    public function getMageCoreModelAppReflection()
    {
        return new \ReflectionClass('Mage_Core_Model_App');
    }
}
