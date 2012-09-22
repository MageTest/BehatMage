<?php

namespace spec\MageTest\MagentoExtension\Context;

use PHPSpec2\Specification;

class MagentoContext implements Specification
{
    function it_should_exist()
    {
        $this->object->shouldNotBe(null);
    }
}