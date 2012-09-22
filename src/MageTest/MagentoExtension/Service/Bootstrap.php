<?php

namespace MageTest\MagentoExtension\Service;

class Bootstrap
{

    public function createMage()
    {
        return \Mage::app();
    }
}
