<?php

namespace MageTest\MagentoExtension\Fixture;
use MageTest\MagentoExtension\Fixture;

/**
 * Product fixtures functionality provider
 *
 * @package MagentoExtension
 */
class User implements FixtureInterface
{
    private $_modelFactory = null;

    /**
     * @param $productModelFactory \Closure optional
     */
    public function __construct($userModelFactory = null)
    {
        $this->_modelFactory = $userModelFactory ?: $this->defaultModelFactory();
    }

    /**
     * Create a fixture in Magento DB using the given attributes map and return its ID
     *
     * @param $attributes array attributes map using 'label' => 'value' format
     *
     * @return int
     */
    public function create(array $attributes) 
    {
        $modelFactory = $this->_modelFactory;
        $model = $modelFactory();

        $model->setData($attributes);
        if ($model->userExists()) {
            throw new \Exception('Username provided to user fixture should not be existing');
        }
        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
        $model->save();
        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::DISTRO_STORE_ID);

        return $model->getId();
    }

    /**
     * Delete the requested fixture from Magento DB
     *
     * @param $identifier int object identifier
     *
     * @return null
     */
    public function delete($identifier)
    {
        $modelFactory = $this->_modelFactory;
        $model = $modelFactory();

        $model->load($identifier);
        $model->delete();
    }

    /**
     * retrieve default product model factory
     *
     * @return \Closure
     */
    public function defaultModelFactory()
    {
        return function () {
            return \Mage::getModel('catalog/product');
        };
    }
}