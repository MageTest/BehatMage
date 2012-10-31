<?php

namespace spec\MageTest\MagentoExtension\Context;

use PHPSpec2\ObjectBehavior;

class ClassGuesser extends ObjectBehavior
{
    function it_should_guess_which_context_to_add()
    {
        $this->guess()->shouldReturn(
            array('MageTest\MagentoExtension\Context\MagentoAwareInterface')
        );
    }
}
