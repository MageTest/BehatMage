<?php

namespace spec\MageTest\MagentoExtension\Fixture;

use PHPSpec2\Specification;

class FixtureFactory implements Specification
{
    function it_should_return_a_product_fixture_when_requested()
    {
        $this->fixtureFactory->create('product')->shouldBeAnInstanceOf('\MageTest\MagentoExtension\Fixture\Product');
    }

    function it_should_throw_when_invalid_generator_requested()
    {
        $this->fixtureFactory->shouldThrow('\InvalidArgumentException')
            ->during(array($this->fixtureFactory, 'create'), array('invalid'));
    }
}
