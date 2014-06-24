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
 * @subpackage Fixture
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace MageTest\MagentoExtension\Fixture;
use MageTest\MagentoExtension\Fixture;
use MageTest\MagentoExtension\Helper\Website;
use MageTest\MagentoExtension\Helper\WebsiteHelperInterface;

/**
 * Product fixtures functionality provider
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Fixture
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class Product implements FixtureInterface
{
    const MAGE_PRODUCT_MODEL_PATH = 'catalog/product';

    private $modelFactory = null;
    private $model;
    private $defaultAttributes;

    /**
     * @var \MageTest\MagentoExtension\Helper\WebsiteHelperInterface
     */
    protected $websiteHelper;

    /**
     * @param null $productModelFactory
     * @param null $websiteHelperFactory
     */
    public function __construct($productModelFactory = null, $websiteHelperFactory = null)
    {
        $this->modelFactory  = $productModelFactory  ?: $this->defaultModelFactory();
        $this->websiteHelper = $websiteHelperFactory ?: $this->defaultWebsiteHelperFactory();
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
        $modelFactory = $this->modelFactory;
        $this->model  = $modelFactory();

        $attributes   = $this->sanitizeAttributes($attributes);

        $id = $this->model->getIdBySku($attributes['sku']);
        if ($id) {
            $this->model->load($id);
        }

        if (!empty($attributes['type_id'])) {
            $this->model->setTypeId($attributes['type_id']);
        }


        $this->validateAttributes(array_keys($attributes));

        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);

        $this->model
            ->setWebsiteIds(array_map(function($website) {
                return $website->getId();
            }, $this->websiteHelper->getWebsites()))
            ->setData($this->mergeAttributes($attributes))
            ->save();

        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::DISTRO_STORE_ID);

        return $this->model->getId();
    }

    private function mergeAttributes($attributes)
    {
        return array_merge($this->getDefaultAttributes(), $this->model->getData(), $attributes);
    }

    private function validateAttributes($attributes)
    {
        foreach ($attributes as $attribute) {
            if (!$this->attributeExists($attribute)) {
                throw new \RuntimeException("$attribute is not yet defined as an attribute of Product");
            }
        }
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
        $modelFactory = $this->modelFactory;
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
            return \Mage::app()->getModel(self::MAGE_PRODUCT_MODEL_PATH);
        };
    }

    /**
     * Retrieve default Website helper used in the class
     *
     * @return Website
     */
    private function defaultWebsiteHelperFactory()
    {
        return new Website();
    }


    protected function sanitizeAttributes($attributes)
    {
        foreach ($attributes as $key => $value) {
            if (!$this->attributeExists($key) && empty($value)) {
                unset($attributes[$key]);
            }
        }

        return $attributes;
    }

    private function attributeExists($attribute)
    {
        return in_array($attribute, array_keys($this->getDefaultAttributes()));
    }

    protected function getDefaultAttributes()
    {
        $typeId = $this->model->getTypeId();

        if ($this->defaultAttributes[$typeId]) {
            return $this->defaultAttributes[$typeId];
        }

        $eavAttributes  = $this->model->getAttributes();
        $attributeCodes = array();
        foreach ($eavAttributes as $attributeObject) {
            $attributeCodes[$attributeObject->getAttributeCode()] = '';
        }

        return $this->defaultAttributes[$typeId] = array_merge($attributeCodes, array(
            'sku'               => '',
            'attribute_set_id'  => $this->retrieveDefaultAttributeSetId(),
            'name'              => 'product name',
            'weight'            => 2,
            'visibility'        => \Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
            'status'            => \Mage_Catalog_Model_Product_Status::STATUS_ENABLED,
            'price'             => 100,
            'description'       => 'Product description',
            'short_description' => 'Product short description',
            'tax_class_id'      => 1,
            'type_id'           => \Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
            'stock_data'        => array( 'is_in_stock' => 1, 'qty' => 99999 ),
            'website_ids'       => $this->websiteHelper->getWebsiteIds(),
        ));
    }

    protected function retrieveDefaultAttributeSetId()
    {
        return $this->model->getResource()
            ->getEntityType()
            ->getDefaultAttributeSetId();
    }
}
