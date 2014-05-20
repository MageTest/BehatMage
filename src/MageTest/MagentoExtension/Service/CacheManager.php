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

use MageTest\MagentoExtension\Servcie\Cache\ConfigurationCache;

use Mage_Core_Model_App as MageApp;

/**
 * CacheManager
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Service
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class CacheManager
{
    protected $bootstrap;

    public function __construct(Bootstrap $bootstrap)
    {
        $this->bootstrap = $bootstrap;
    }

    /**
     * Array of cache sections / tags to be modified
     *
     * @var array
     **/
    private $sections = array();

    public function addSection($name, $service)
    {
        $this->sections[$name] = $service;
    }

    public function clear()
    {
        if (empty($this->sections)) {
            $this->addDefaultSections();
        }
        foreach ($this->sections as $name => $service) {
            $service->clear();
        }
    }

    private function addDefaultSections()
    {
        $app = $this->bootstrap->app();
        $this->sections['config'] = new Cache\ConfigurationCache($app);
    }
}
