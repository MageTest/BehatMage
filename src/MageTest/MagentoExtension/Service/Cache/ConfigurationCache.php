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
namespace MageTest\MagentoExtension\Service\Cache;

use Mage_Core_Model_App;

/**
 * ConfigurationCache
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Service\Cache
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class ConfigurationCache
{
    /**
     * Internal instance of MageApp
     *
     * @var Mage_Core_Model_App
     **/
    private $mageApp;

    public function __construct(Mage_Core_Model_App $mageApp)
    {
        $this->mageApp = $mageApp;
    }

    // FIXME This is brutal but it is late
    public function clear()
    {
        $this->mageApp->getCacheInstance()->flush();
    }
}
