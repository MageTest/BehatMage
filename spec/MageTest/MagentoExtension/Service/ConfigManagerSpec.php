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

use MageTest\MagentoExtension\Service\Bootstrap;
use MageTest\MagentoExtension\Service\Config\CoreConfig;
use PhpSpec\ObjectBehavior;

/**
 * ConfigManagerSpec
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Service
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class ConfigManagerSpec extends ObjectBehavior
{
    function let(Bootstrap $bootstrap, CoreConfig $coreConfig)
    {
        $this->beConstructedWith($bootstrap, $coreConfig);
    }

    function it_should_set_core_config($coreConfig)
    {
        $coreConfig->set('test/path/to/config', 'value', 1)->shouldBeCalled();

        $this->setCoreConfig('test/path/to/config', 'value', 1);
    }
}
