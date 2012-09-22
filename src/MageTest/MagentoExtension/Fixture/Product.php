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
        $model->setData($attributes)->save();
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
