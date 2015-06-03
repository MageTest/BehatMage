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

use MageTest\MagentoExtension\Fixture\FixtureInterface;
use MageTest\MagentoExtension\Fixture\Product;
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
    function let(FixtureInterface $product)
    {
        $this->addFixture('product', $product);
    }

    function it_is_constructed_without_fixtures()
    {
        $this->beConstructedWith();
        $this->shouldThrow('\RuntimeException')->during('create', array('product'));
    }

    function it_should_throw_when_invalid_generator_requested()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringCreate('invalid');
    }

    function it_should_be_created_with_an_empty_registry()
    {
        $this->getRegistry()->shouldBeLike(array());
    }

    function it_should_call_create_on_the_fixture_and_pass_data(FixtureInterface $product)
    {
        $product->create(array('sku' => '123'))->shouldBeCalled();
        $this->create('product', array('sku' => '123'));
    }

    function it_should_add_the_fixture_to_the_registry_after_creation(FixtureInterface $product)
    {
        $this->create('product');
        $this->getRegistry()->shouldBeLike(array($product));
    }

    function it_should_clean_the_registry_when_clean_is_envoked(FixtureInterface $product)
    {
        $data = array('id' => 42);
        $product->create($data)->shouldBeCalled();
        $product->delete()->shouldBeCalled();
        $this->create('product', $data);
        $this->clean();
        $this->getRegistry()->shouldBe(array());
    }

}
