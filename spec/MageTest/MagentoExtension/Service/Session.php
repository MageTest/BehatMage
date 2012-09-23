<?php

namespace spec\MageTest\MagentoExtension\Service;

use PHPSpec2\Specification;

class Session implements Specification
{
    function described_with()
    {
        \Mage::app();
        $this->session->isAnInstanceOf('MageTest\MagentoExtension\Service\Session');
    }
}
