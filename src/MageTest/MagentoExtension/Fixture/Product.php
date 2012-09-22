<?php

namespace MageTest\MagentoExtension\Fixture;

/**
 * Product fixtures functionality provider
 *
 * @package MagentoExtension
 */
class Product
{
    private $_modelFactory = null;

    /**
     * @param $productModelFactory \Closure optional
     */
    public function __construct($productModelFactory = null)
    {
        $this->_modelFactory = $productModelFactory ?: $this->defaultModelFactory();
    }

    /**
     * Create a product fixture using the given attributes map
     *
     * @param $attributes array product attributes map using 'label' => 'value' format
     *
     * @return null
     */
    public function create($attributes)
    {
        $modelFactory = $this->_modelFactory;
        $model = $modelFactory();

        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
        $model->setData($attributes)->save();
        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::DISTRO_STORE_ID);
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
