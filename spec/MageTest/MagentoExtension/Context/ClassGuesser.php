<?php

namespace spec\MageTest\MagentoExtension\Context;

use PHPSpec2\Specification;

class ClassGuesser implements Specification
{
    function it_should_guess_what_context_to_add()
    {
        $this->classGuesser->guess()->shouldReturn(
            array('MageTest\MagentoExtension\Context\MagentoAwareInterface')
        );
    }
}