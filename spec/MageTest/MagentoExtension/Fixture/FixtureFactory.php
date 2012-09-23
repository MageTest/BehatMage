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

    function it_should_clean_the_registry_when_clean_is_envoked()
    {
        $this->fixtureFactory->clean();
        $this->fixtureFactory->getRegistry()->shouldBe(array());
    }

    function it_should_add_any_requested_fixtures_to_the_registry()
    {
        $fixture = $this->fixtureFactory->create('product');
        $this->fixtureFactory->getRegistry()->shouldBeLike(array($fixture));
    }
}
