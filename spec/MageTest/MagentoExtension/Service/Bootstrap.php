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

use PHPSpec2\ObjectBehavior;

/**
 * Bootstrap
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Service
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class Bootstrap extends ObjectBehavior
{
    function it_bootstraps_a_mage_app()
    {
        $this->app()->shouldBeAnInstanceOf('Mage_Core_Model_App');
    }

    function it_provides_a_reflection_of_Mage()
    {
        $this->getMageReflection()->shouldBeAnInstanceOf('ReflectionClass');
    }

    function it_provides_a_reflection_of_Mage_Core_Model_App()
    {
        $this->getMageCoreModelAppReflection()->shouldBeAnInstanceOf('ReflectionClass');
    }
}
