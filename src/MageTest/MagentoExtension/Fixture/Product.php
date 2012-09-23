<?php

namespace MageTest\MagentoExtension\Fixture;
use MageTest\MagentoExtension\Fixture;

/**
 * Product fixtures functionality provider
 *
 * @package MagentoExtension
 */
class Product implements Fixture
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
     * @return int
     */
    public function create(array $attributes)
    {
        $modelFactory = $this->_modelFactory;
        $model = $modelFactory();

        $id = $model->getIdBySku($attributes['sku']);
        if ($id) {
            $model->load($id);
        }

        $attributes = array_merge($this->_getDefaultAttributes($model), $model->getData(), $attributes);

        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
        $model->setData($attributes)->save();
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

    protected function _getDefaultAttributes($model)
    {
        return array(
            'attribute_set_id' => $this->_retrieveDefaultAttributeSetId($model),
            'name' => 'product name',
            'weight' => 2,
            'visibility'=> \Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
            'status' => \Mage_Catalog_Model_Product_Status::STATUS_ENABLED,
            'price' => 100,
            'description' => 'Product description',
            'short_description' => 'Product short description',
            'tax_class_id' => 1,
            'type_id' => \Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
            'stock_data' => array( 'is_in_stock' => 1, 'qty' => 99999 )
        );
    }

    protected function _retrieveDefaultAttributeSetId($model)
    {
        return $model->getResource()
            ->getEntityType()
            ->getDefaultAttributeSetId();
    }
}