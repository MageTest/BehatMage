<?php
/**
 * BehatMage
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License, that is bundled with this
 * package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 *
 * http://opensource.org/licenses/MIT
 *
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email
 * to <magetest@sessiondigital.com> so we can send you a copy immediately.
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Fixure
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace spec\MageTest\MagentoExtension\Fixture;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


/**
 * FixtureFactorySpec
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Fixture
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class FixtureFactorySpec extends ObjectBehavior
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
