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
 * @subpackage Service
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace spec\MageTest\MagentoExtension\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


/**
 * CacheManagerSpec
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Service
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class CacheManagerSpec extends ObjectBehavior
{
    /**
     * @param MageTest\MagentoExtension\Service\Bootstrap $bootstrap
     */
    function let($bootstrap)
    {
        $this->beConstructedWith($bootstrap);
    }

    /**
     * @param MageTest\MagentoExtension\Service\Cache\ConfigurationCache $cache
     */
    function it_should_clear_configuration_by_default($cache)
    {
        $this->addSection('configuration', $cache);

        $cache->clear()->shouldBeCalled();

        $this->clear();
    }
}
