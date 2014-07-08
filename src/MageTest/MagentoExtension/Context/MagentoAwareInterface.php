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
namespace MageTest\MagentoExtension\Context;

use Mage_Core_Model_App as MageApp;
use MageTest\MagentoExtension\Service\ConfigManager;
use MageTest\MagentoExtension\Service\CacheManager;
use MageTest\MagentoExtension\Fixture\FixtureFactory;
use MageTest\MagentoExtension\Service\Session;

/**
 * MagentoAwareInterface
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Context
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
interface MagentoAwareInterface
{
    /**
     * @return void
     */
    public function setApp(MageApp $app);

    /**
     * @return void
     */
    public function setConfigManager(ConfigManager $config);

    /**
     * @return void
     */
    public function setCacheManager(CacheManager $cache);

    /**
     * @return void
     */
    public function setFixtureFactory(FixtureFactory $factory);

    /**
     * @return void
     */
    public function setSessionService(Session $session);
}
