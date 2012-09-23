<?php

namespace MageTest\MagentoExtension\Service\Config;

use Mage_Core_Model_Config_Data as CoreConfigModel;

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