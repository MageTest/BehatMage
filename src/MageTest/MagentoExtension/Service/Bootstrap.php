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
 * @copyright  Copyright (c) 2012-2014 MageTest team and contributors.
 */
namespace MageTest\MagentoExtension\Service;

/**
 * Bootstrap
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Service
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class Bootstrap
{
    public function app()
    {
        return \Mage::app();
    }

    public function getMageReflection()
    {
        return new \ReflectionClass('Mage');
    }

    public function getMageCoreModelAppReflection()
    {
        return new \ReflectionClass('Mage_Core_Model_App');
    }
}
