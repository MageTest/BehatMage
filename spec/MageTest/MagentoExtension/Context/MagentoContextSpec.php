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
 * @subpackage Context
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace spec\MageTest\MagentoExtension\Context;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


/**
 * MagentoContextSpec
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Context\Initializer
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class MagentoContextSpec extends ObjectBehavior
{
    function it_should_support_mink()
    {
        $this->shouldBeAnInstanceOf("Behat\MinkExtension\Context\MinkAwareInterface");
    }

    function it_should_add_some_magento_goodies()
    {
        $this->shouldBeAnInstanceOf("MageTest\MagentoExtension\Context\MagentoAwareInterface");
    }

}
