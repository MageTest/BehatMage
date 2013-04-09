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
namespace MageTest\MagentoExtension\Service;

use MageTest\MagentoExtension\Service\Config\CoreConfig,
    MageTest\MagentoExtension\Service\Bootstrap;

/**
 * ConfigManager
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Service
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class ConfigManager
{
    protected $bootstrap;
    protected $coreConfig;

    public function __construct(Bootstrap $bootstrap, CoreConfig $coreConfig)
    {
        $this->bootstrap = $bootstrap;
        $this->coreConfig = $coreConfig;
    }

    public function setCoreConfig($path, $value, $scope = null)
    {
        $this->coreConfig->set($path, $value, $scope);
    }
}
