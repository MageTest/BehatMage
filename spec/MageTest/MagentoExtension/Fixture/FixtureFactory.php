<?php

namespace spec\MageTest\MagentoExtension\Fixture;

use PHPSpec2\ObjectBehavior;

class FixtureFactory extends ObjectBehavior
{
    function it_should_return_a_product_fixture_when_requested()
    {
        $this->create('product')->shouldBeAnInstanceOf('\MageTest\MagentoExtension\Fixture\Product');
    }

    function it_should_throw_when_invalid_generator_requested()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringCreate('invalid');
    }

    function it_should_clean_the_registry_when_clean_is_envoked()
    {
        $this->clean();

        $this->getRegistry()->shouldBe(array());
    }

    function it_should_add_any_requested_fixtures_to_the_registry()
    {
        $fixture = $this->create('product');

        $this->getRegistry()->shouldBeLike(array($fixture->getWrappedSubject()));
    }
}
