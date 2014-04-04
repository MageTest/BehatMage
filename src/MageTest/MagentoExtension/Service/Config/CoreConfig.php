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
 * @copyright  Copyright (c) 2012-2014 MageTest team and contributors.
 */
namespace MageTest\MagentoExtension\Service\Config;

use Mage_Core_Model_Config_Data as CoreConfigModel;

/**
 * CoreConfig
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Service\Config
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class CoreConfig
{
    protected $coreConfigModel;

    public function __construct(CoreConfigModel $coreConfigModel)
    {
        $this->coreConfigModel = $coreConfigModel;
    }

    public function set($path, $value, $scope = null)
    {
        $configCollection = $this->coreConfigModel->getCollection();

        $configCollection->addFieldToFilter('path', array("eq" => $path));
        if (is_string($scope)) {
            $configCollection->addFieldToFilter('scope', array("eq" => $scope));
        }
        $configCollection->load();

        // If existing config does not exist create it
        if ($configCollection->count() == 0) {
            $configData = \Mage::getModel('core/config_data');
            $configData->setPath($path);
            $configData->setValue($value);
            // Calculate scope
            $scope = ($scope)? $scope : 'default';
            $configData->setScope($scope);
            $configData->save();
            $this->_newConfigValues[] = $configData;
        }
        foreach ($configCollection as $config) {
            $this->_originalConfigValues[] = $config;
            $config->setValue($value);
            $config->save();
        }
    }

    public static function remove($path, $scope = null)
    {
        $configCollection = $this->coreConfigModel->getCollection();
        $configCollection->addFieldToFilter('path', array("eq" => $path));
        if (is_string($scope)) {
            $configCollection->addFieldToFilter('scope', array("eq" => $scope));
        }
        $configCollection->load();
        foreach ($configCollection as $config) {
            $this->_removedConfigValues[] = $config;
            $config->delete();
        }
    }
}