<?php

namespace spec\MageTest\MagentoExtension\Context;

use PHPSpec2\ObjectBehavior;

class MagentoContext extends ObjectBehavior
{
    function it_should_support_mink()
    {
        $this->shouldBeAnInstanceOf("Behat\MinkExtension\Context\MinkAwareInterface");
    }

    function it_should_add_some_magento_goodies()
    {
        $this->shouldBeAnInstanceOf("MageTest\MagentoExtension\Context\MagentoAwareInterface");
    }

}
