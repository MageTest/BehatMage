<?php

namespace spec\MageTest\MagentoExtension\Fixture;

use PHPSpec2\Specification;

class Factory implements Specification
{
    function it_should_exist()
    {
        $this->object->shouldNotBe(null);
    }

    function it_should_return_a_product_fixture_when_requested()
    {
        $this->factory->create('product')->shouldBeAnInstanceOf('\MageTest\MagentoExtension\Fixture\Product');
    }

    function it_should_return_a_user_fixture_when_requested()
    {
        $this->factory->create('user')->shouldBeAnInstanceOf('\MageTest\MagentoExtension\Fixture\User');
    }

    function it_should_throw_when_invalid_generator_requested()
    {
        $this->factory->shouldThrow('\InvalidArgumentException')
            ->during(array($this->factory, 'create'), array('invalid'));
    }
}
