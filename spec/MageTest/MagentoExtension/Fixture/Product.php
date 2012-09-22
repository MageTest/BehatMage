<?php

namespace spec\MageTest\MagentoExtension\Fixture;

use PHPSpec2\Specification;

class Product implements Specification
{
    function it_should_exist()
    {
        $this->object->shouldNotBe(null);
    }
}