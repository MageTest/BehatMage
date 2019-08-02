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
 * @subpackage Service\Cache
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace spec\MageTest\MagentoExtension\Service\Cache;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


/**
 * ConfigurationCacheSpec
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Service\Cache
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class ConfigurationCacheSpec extends ObjectBehavior
{
    function let(\Mage_Core_Model_App $mageApp, \Mage_Core_Model_Cache $cacheInstance)
    {
        $mageApp->getCacheInstance()->willReturn($cacheInstance);
        $mageApp->cleanCache(Argument::any())->shouldBeCalled();
        $cacheInstance->getTagsByType(Argument::any())->willReturn(array("config"));

        $this->beConstructedWith($mageApp);
    }

    function it_should_clear_the_configuration_cache()
    {
        $this->clear();
    }
}
