<?php

namespace spec\MageTest\MagentoExtension\Context;

use PHPSpec2\Specification;

class MagentoContext implements Specification
{
    function it_should_support_mink()
    {
        $this->magentoContext->shouldBeAnInstanceOf("Behat\MinkExtension\Context\MinkAwareInterface");
    }

    function it_should_add_some_magento_goodies()
    {
        $this->magentoContext->shouldBeAnInstanceOf("MageTest\MagentoExtension\Context\MagentoAwareInterface");
    }

}
